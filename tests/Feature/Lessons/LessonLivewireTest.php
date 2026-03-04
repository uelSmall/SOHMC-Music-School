<?php

namespace Tests\Feature\Lessons;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Lesson\Models\Lesson;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LessonLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $viewBackend = Permission::findOrCreate('view_backend', 'web');
        $editBackend = Permission::findOrCreate('edit_backend', 'web');
        $manageLessons = Permission::findOrCreate('manage_lessons', 'web');

        Role::findOrCreate('administrator', 'web')->syncPermissions([$viewBackend, $editBackend, $manageLessons]);
        Role::findOrCreate('teacher', 'web')->syncPermissions([$manageLessons]);
    }

    #[Test]
    public function lesson_list_displays_all_lessons()
    {
        $user = $this->createAdministratorUser();

        $lessons = Lesson::factory(3)->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->assertSee($lessons[0]->title)
            ->assertSee($lessons[1]->title)
            ->assertSee($lessons[2]->title);
    }

    #[Test]
    public function teacher_only_sees_their_own_lessons_in_list()
    {
        $teacher = $this->createTeacherUser();
        $otherTeacher = $this->createTeacherUser();

        $ownedLesson = Lesson::factory()->create(['teacher_id' => $teacher->id, 'title' => 'Owned Lesson']);
        $otherLesson = Lesson::factory()->create(['teacher_id' => $otherTeacher->id, 'title' => 'Other Lesson']);

        Livewire::actingAs($teacher)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->assertSee($ownedLesson->title)
            ->assertDontSee($otherLesson->title);
    }

    #[Test]
    public function lesson_list_filters_by_status()
    {
        $user = $this->createAdministratorUser();

        Lesson::factory(2)->published()->create();
        Lesson::factory(1)->draft()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->set('statusFilter', 'published')
            ->assertSee('Published');
    }

    #[Test]
    public function lesson_list_searches_by_title()
    {
        $user = $this->createAdministratorUser();

        Lesson::factory()->create(['title' => 'PHP Basics']);
        Lesson::factory()->create(['title' => 'Laravel Advanced']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->set('search', 'PHP')
            ->assertSee('PHP Basics')
            ->assertDontSee('Laravel Advanced');
    }

    #[Test]
    public function lesson_list_sorts_by_column()
    {
        $user = $this->createAdministratorUser();

        Lesson::factory()->create(['title' => 'Zebra']);
        Lesson::factory()->create(['title' => 'Apple']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->call('sort', 'title')
            ->assertSet('sortBy', 'title')
            ->assertSet('sortDir', 'asc');
    }

    #[Test]
    public function lesson_list_deletes_lesson()
    {
        $user = $this->createAdministratorUser();

        $lesson = Lesson::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->call('delete', $lesson->id);

        $this->assertSoftDeleted($lesson);
    }

    #[Test]
    public function teacher_cannot_delete_another_teachers_lesson()
    {
        $teacher = $this->createTeacherUser();
        $otherTeacher = $this->createTeacherUser();
        $lesson = Lesson::factory()->create(['teacher_id' => $otherTeacher->id]);

        Livewire::actingAs($teacher)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->call('delete', $lesson->id)
            ->assertForbidden();

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id, 'deleted_at' => null]);
    }

    #[Test]
    public function lesson_form_creates_new_lesson()
    {
        $user = $this->createAdministratorUser();

        $teacher = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', 'New Lesson')
            ->set('slug', 'new-lesson')
            ->set('content', 'Lesson content here')
            ->set('description', 'A new lesson')
            ->set('status', 'draft')
            ->set('teacher_id', $teacher->id)
            ->call('save');

        $this->assertDatabaseHas('lessons', [
            'title' => 'New Lesson',
            'slug' => 'new-lesson',
            'teacher_id' => $teacher->id,
        ]);
    }

    #[Test]
    public function lesson_form_updates_existing_lesson()
    {
        $user = $this->createAdministratorUser();

        $lesson = Lesson::factory()->create();
        $teacher = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class, ['lesson' => $lesson])
            ->assertSet('title', $lesson->title)
            ->set('title', 'Updated Lesson')
            ->set('content', 'Updated content')
            ->set('teacher_id', $teacher->id)
            ->call('save');

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Updated Lesson',
            'teacher_id' => $teacher->id,
        ]);
    }

    #[Test]
    public function lesson_form_assigns_students()
    {
        $user = $this->createAdministratorUser();

        $students = User::factory(3)->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', 'New Lesson')
            ->set('slug', 'new-lesson')
            ->set('content', 'Lesson content')
            ->set('status', 'draft')
            ->set('student_ids', $students->pluck('id')->toArray())
            ->call('save');

        $lesson = Lesson::where('slug', 'new-lesson')->first();
        $this->assertEquals(3, $lesson->students()->count());
    }

    #[Test]
    public function lesson_form_validates_required_fields()
    {
        $user = $this->createAdministratorUser();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors('title');
    }

    #[Test]
    public function lesson_form_validates_unique_slug()
    {
        $user = $this->createAdministratorUser();

        Lesson::factory()->create(['slug' => 'existing-slug']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', 'New Lesson')
            ->set('slug', 'existing-slug')
            ->set('content', 'Content')
            ->call('save')
            ->assertHasErrors('slug');
    }

    #[Test]
    public function teacher_cannot_mount_form_with_another_teachers_lesson()
    {
        $teacher = $this->createTeacherUser();
        $otherTeacher = $this->createTeacherUser();
        $lesson = Lesson::factory()->create(['teacher_id' => $otherTeacher->id]);

        Livewire::actingAs($teacher)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class, ['lesson' => $lesson])
            ->assertForbidden();
    }

    #[Test]
    public function teacher_can_access_teacher_lesson_create_page()
    {
        $teacher = $this->createTeacherUser();

        $this->actingAs($teacher)
            ->get(route('teacher.lessons.create'))
            ->assertOk();
    }

    #[Test]
    public function teacher_created_lesson_is_forced_to_current_teacher()
    {
        $teacher = $this->createTeacherUser();
        $otherTeacher = $this->createTeacherUser();

        Livewire::actingAs($teacher)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', 'Teacher Lesson')
            ->set('slug', 'teacher-lesson')
            ->set('content', 'Teacher content')
            ->set('status', 'draft')
            ->set('teacher_id', $otherTeacher->id)
            ->call('save');

        $this->assertDatabaseHas('lessons', [
            'slug' => 'teacher-lesson',
            'teacher_id' => $teacher->id,
        ]);
    }

    private function createAdministratorUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('administrator');

        return $user;
    }

    private function createTeacherUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('teacher');

        return $user;
    }
}

