<?php

namespace Tests\Feature\Booking;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Modules\Booking\Database\Factories\BookedLessonFactory;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LessonLifecycleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $viewAssignedLessons = Permission::findOrCreate('view_assigned_lessons', 'web');
        $manageLessons = Permission::findOrCreate('manage_lessons', 'web');

        Role::findOrCreate('student', 'web')->syncPermissions([$viewAssignedLessons]);
        Role::findOrCreate('teacher', 'web')->syncPermissions([$manageLessons]);
    }

    #[Test]
    public function teacher_can_view_the_lesson_management_index(): void
    {
        $teacher = $this->createTeacher();

        $lesson = $this->createBookedLesson($teacher, $this->createStudent());

        $this->actingAs($teacher)
            ->get(route('teacher.booking-management.index'))
            ->assertOk()
            ->assertSee($lesson->student->name)
            ->assertSee($lesson->instrument->name);
    }

    #[Test]
    public function student_can_view_their_lesson_management_index(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();

        $lesson = $this->createBookedLesson($teacher, $student);

        $this->actingAs($student)
            ->get(route('student.booking-management.index'))
            ->assertOk()
            ->assertSee($lesson->teacher->name)
            ->assertSee($lesson->instrument->name);
    }

    #[Test]
    public function teacher_can_mark_a_lesson_completed(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $lesson = $this->createBookedLesson($teacher, $this->createStudent());

        $this->actingAs($teacher)
            ->patch(route('teacher.booking-management.complete', $lesson))
            ->assertRedirect(route('teacher.booking-management.show', $lesson));

        $this->assertDatabaseHas('booked_lessons', [
            'id' => $lesson->id,
            'status' => LessonStatus::Completed->value,
        ]);
        $this->assertNotNull($lesson->fresh()->completed_at);
    }

    #[Test]
    public function teacher_can_cancel_a_lesson_with_a_reason(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $lesson = $this->createBookedLesson($teacher, $this->createStudent());

        $this->actingAs($teacher)
            ->patch(route('teacher.booking-management.cancel', $lesson), [
                'cancellation_reason' => 'Teacher unavailable.',
            ])
            ->assertRedirect(route('teacher.booking-management.show', $lesson));

        $lesson->refresh();

        $this->assertSame(LessonStatus::Cancelled, $lesson->status);
        $this->assertSame('Teacher unavailable.', $lesson->cancellation_reason);
        $this->assertNotNull($lesson->cancelled_at);
    }

    #[Test]
    public function teacher_can_reschedule_a_lesson_when_no_conflict_exists(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $lesson = $this->createBookedLesson($teacher, $student, [
            'lesson_date' => Carbon::now()->addDays(3)->toDateString(),
            'lesson_start_time' => '10:00:00',
            'lesson_end_time' => '10:30:00',
        ]);

        $this->actingAs($teacher)
            ->patch(route('teacher.booking-management.reschedule', $lesson), [
                'new_date' => Carbon::now()->addDays(4)->toDateString(),
                'new_start_time' => '11:00',
                'new_end_time' => '11:30',
            ])
            ->assertRedirect(route('teacher.booking-management.show', $lesson));

        $lesson->refresh();

        $this->assertSame(Carbon::now()->addDays(4)->toDateString(), $lesson->lesson_date?->toDateString());
        $this->assertSame('11:00:00', $lesson->lesson_start_time);
        $this->assertSame('11:30:00', $lesson->lesson_end_time);
        $this->assertNotNull($lesson->rescheduled_at);
    }

    #[Test]
    public function teacher_cannot_reschedule_into_a_conflicting_lesson(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();

        $lesson = $this->createBookedLesson($teacher, $student, [
            'lesson_date' => Carbon::now()->addDays(5)->toDateString(),
            'lesson_start_time' => '12:00:00',
            'lesson_end_time' => '12:30:00',
        ]);

        $this->createBookedLesson($teacher, $this->createStudent(), [
            'lesson_date' => Carbon::now()->addDays(6)->toDateString(),
            'lesson_start_time' => '12:15:00',
            'lesson_end_time' => '12:45:00',
        ]);

        $this->actingAs($teacher)
            ->patch(route('teacher.booking-management.reschedule', $lesson), [
                'new_date' => Carbon::now()->addDays(6)->toDateString(),
                'new_start_time' => '12:20',
                'new_end_time' => '12:50',
            ])
            ->assertSessionHasErrors(['new_start_time']);
    }

    #[Test]
    public function student_cannot_access_teacher_lesson_management_actions(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $lesson = $this->createBookedLesson($teacher, $student);

        $this->actingAs($student)
            ->get(route('teacher.booking-management.index'))
            ->assertForbidden();

        $this->actingAs($student)
            ->patch(route('teacher.booking-management.complete', $lesson))
            ->assertForbidden();
    }

    private function createTeacher(): User
    {
        $user = User::factory()->create();
        $user->assignRole('teacher');

        return $user;
    }

    private function createStudent(): User
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        return $user;
    }

    private function createBookedLesson(User $teacher, User $student, array $overrides = []): BookedLesson
    {
        $instrument = Instrument::factory()->create(['name' => 'Piano']);
        $teacher->teachingInstruments()->attach($instrument->id);

        $lessonRequest = LessonRequest::factory()->create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'instrument_id' => $instrument->id,
            'requested_date' => $overrides['lesson_date'] ?? Carbon::now()->addDays(7)->toDateString(),
            'requested_start_time' => '10:00:00',
            'requested_end_time' => '10:30:00',
            'lesson_duration' => 30,
        ]);

        return BookedLesson::factory()->create(array_merge([
            'lesson_request_id' => $lessonRequest->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'instrument_id' => $instrument->id,
            'lesson_date' => $overrides['lesson_date'] ?? Carbon::now()->addDays(7)->toDateString(),
            'lesson_start_time' => $overrides['lesson_start_time'] ?? '10:00:00',
            'lesson_end_time' => $overrides['lesson_end_time'] ?? '10:30:00',
            'lesson_duration' => 30,
            'status' => LessonStatus::Scheduled,
        ], $overrides));
    }
}