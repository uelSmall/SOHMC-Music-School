<?php

namespace Tests\Feature\Booking;

use App\Notifications\Booking\LessonConfirmedNotification;
use App\Notifications\Booking\LessonSuggestionAcceptedNotification;
use App\Notifications\Booking\NewLessonRequestNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationWorkflowTest extends TestCase
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
    public function student_submitting_a_request_notifies_the_assigned_teacher(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $instrument = Instrument::factory()->create(['name' => 'Guitar']);

        $teacher->teachingInstruments()->attach($instrument->id);

        $this->actingAs($student)
            ->post(route('student.lesson-requests.store'), [
                'instrument_id' => $instrument->id,
                'teacher_id' => $teacher->id,
                'requested_date' => Carbon::now()->addWeek()->toDateString(),
                'requested_start_time' => '16:00',
                'requested_end_time' => '16:30',
                'lesson_duration' => 30,
                'student_note' => 'Please use the practice room.',
            ])
            ->assertRedirect(route('student.lesson-requests.index'));

        Notification::assertSentTo($teacher, NewLessonRequestNotification::class, function (NewLessonRequestNotification $notification) use ($teacher): bool {
            $data = $notification->toArray($teacher);

            return $data['event'] === NewLessonRequestNotification::eventKey()
                && $data['title'] === 'New Lesson Request';
        });
    }

    #[Test]
    public function teacher_confirming_a_request_notifies_the_student(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $instrument = Instrument::factory()->create(['name' => 'Piano']);

        $teacher->teachingInstruments()->attach($instrument->id);

        $lessonRequest = LessonRequest::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'instrument_id' => $instrument->id,
            'requested_date' => Carbon::now()->addWeek()->toDateString(),
            'requested_start_time' => '16:00',
            'requested_end_time' => '16:30',
            'lesson_duration' => 30,
            'status' => LessonRequestStatus::Pending,
        ]);

        $this->actingAs($teacher)
            ->patch(route('teacher.lesson-requests.confirm', $lessonRequest), [
                'teacher_note' => 'Confirmed.',
            ])
            ->assertRedirect(route('teacher.lesson-requests.show', $lessonRequest));

        Notification::assertSentTo($student, LessonConfirmedNotification::class, function (LessonConfirmedNotification $notification) use ($student): bool {
            $data = $notification->toArray($student);

            return $data['event'] === LessonConfirmedNotification::eventKey()
                && $data['title'] === 'Lesson Confirmed';
        });

        $this->assertDatabaseHas('booked_lessons', [
            'lesson_request_id' => $lessonRequest->id,
            'status' => LessonStatus::Scheduled->value,
        ]);
    }

    #[Test]
    public function student_accepting_a_suggested_time_notifies_the_teacher(): void
    {
        Notification::fake();

        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $instrument = Instrument::factory()->create(['name' => 'Violin']);

        $teacher->teachingInstruments()->attach($instrument->id);

        $lessonRequest = LessonRequest::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'instrument_id' => $instrument->id,
            'requested_date' => Carbon::now()->addWeek()->toDateString(),
            'requested_start_time' => '15:00',
            'requested_end_time' => '15:30',
            'suggested_date' => Carbon::now()->addDays(10)->toDateString(),
            'suggested_start_time' => '17:00',
            'suggested_end_time' => '17:30',
            'lesson_duration' => 30,
            'status' => LessonRequestStatus::TeacherRescheduled,
        ]);

        $this->actingAs($student)
            ->patch(route('student.lesson-requests.accept-suggestion', $lessonRequest))
            ->assertRedirect(route('student.lesson-requests.index'));

        Notification::assertSentTo($teacher, LessonSuggestionAcceptedNotification::class, function (LessonSuggestionAcceptedNotification $notification) use ($teacher): bool {
            $data = $notification->toArray($teacher);

            return $data['event'] === LessonSuggestionAcceptedNotification::eventKey()
                && $data['title'] === 'Suggestion Accepted';
        });

        $this->assertDatabaseHas('booked_lessons', [
            'lesson_request_id' => $lessonRequest->id,
            'status' => LessonStatus::Scheduled->value,
        ]);
    }

    #[Test]
    public function users_cannot_open_someone_elses_notification(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $instrument = Instrument::factory()->create(['name' => 'Drums']);

        $teacher->teachingInstruments()->attach($instrument->id);

        $lessonRequest = LessonRequest::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'instrument_id' => $instrument->id,
            'requested_date' => Carbon::now()->addWeek()->toDateString(),
            'requested_start_time' => '14:00',
            'requested_end_time' => '14:30',
            'lesson_duration' => 30,
            'status' => LessonRequestStatus::Pending,
        ]);

        $teacher->notify(new NewLessonRequestNotification($lessonRequest));
        $notification = $teacher->notifications()->latest()->first();

        $this->actingAs($student)
            ->get(route('notifications.open', $notification->id))
            ->assertNotFound();
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
}