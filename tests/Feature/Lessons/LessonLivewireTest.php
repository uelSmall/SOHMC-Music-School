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
        
        // Create required permissions
        Permission::create(['name' => 'view_backend', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_backend', 'guard_name' => 'web']);
    }

    #[Test]
    public function lesson_list_displays_all_lessons()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view_backend');

        $lessons = Lesson::factory(3)->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->assertSee($lessons[0]->title)
            ->assertSee($lessons[1]->title)
            ->assertSee($lessons[2]->title);
    }

    #[Test]
    public function lesson_list_filters_by_status()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view_backend');

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
        $user = User::factory()->create();
        $user->givePermissionTo('view_backend');

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
        $user = User::factory()->create();
        $user->givePermissionTo('view_backend');

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
        $user = User::factory()->create();
        $user->givePermissionTo('view_backend');

        $lesson = Lesson::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonList::class)
            ->call('delete', $lesson->id);

        $this->assertSoftDeleted($lesson);
    }

    #[Test]
    public function lesson_form_creates_new_lesson()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('edit_backend');
        
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
        $user = User::factory()->create();
        $user->givePermissionTo('edit_backend');

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
        $user = User::factory()->create();
        $user->givePermissionTo('edit_backend');

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
        $user = User::factory()->create();
        $user->givePermissionTo('edit_backend');

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors('title');
    }

    #[Test]
    public function lesson_form_validates_unique_slug()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('edit_backend');

        Lesson::factory()->create(['slug' => 'existing-slug']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Backend\Lessons\LessonForm::class)
            ->set('title', 'New Lesson')
            ->set('slug', 'existing-slug')
            ->set('content', 'Content')
            ->call('save')
            ->assertHasErrors('slug');
    }
}

