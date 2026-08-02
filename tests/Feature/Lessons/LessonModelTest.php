<?php

namespace Tests\Feature\Lessons;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Enums\LessonStatus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LessonModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function lesson_can_be_created_with_factory()
    {
        $lesson = Lesson::factory()->create();

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => $lesson->title,
        ]);
    }

    #[Test]
    public function lesson_has_teacher_relationship()
    {
        $teacher = User::factory()->create();
        $lesson = Lesson::factory()->create(['teacher_id' => $teacher->id]);

        $this->assertEquals($teacher->id, $lesson->teacher->id);
        $this->assertInstanceOf(User::class, $lesson->teacher);
    }

    #[Test]
    public function lesson_can_have_multiple_students()
    {
        $lesson = Lesson::factory()->create();
        $students = User::factory(3)->create();

        $lesson->students()->attach($students->pluck('id'));

        $this->assertEquals(3, $lesson->students()->count());
    }

    #[Test]
    public function published_lessons_scope_returns_only_published()
    {
        Lesson::factory(2)->published()->create();
        Lesson::factory(1)->draft()->create();

        $published = Lesson::published()->count();

        $this->assertEquals(2, $published);
    }

    #[Test]
    public function ordered_scope_sorts_by_order()
    {
        Lesson::factory()->create(['order' => 3]);
        Lesson::factory()->create(['order' => 1]);
        Lesson::factory()->create(['order' => 2]);

        $lessons = Lesson::ordered()->get();

        $this->assertEquals(1, $lessons[0]->order);
        $this->assertEquals(2, $lessons[1]->order);
        $this->assertEquals(3, $lessons[2]->order);
    }

    #[Test]
    public function lesson_can_be_soft_deleted()
    {
        $lesson = Lesson::factory()->create();

        $lesson->delete();

        $this->assertSoftDeleted($lesson);
    }

    #[Test]
    public function lesson_casts_status_to_enum()
    {
        $lesson = Lesson::factory()->published()->create();

        $this->assertInstanceOf(\Modules\Lesson\Enums\LessonStatus::class, $lesson->status);
    }

    #[Test]
    public function lesson_casts_published_at_to_datetime()
    {
        $lesson = Lesson::factory()->published()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $lesson->published_at);
    }

    #[Test]
    public function lesson_can_have_file_path()
    {
        $lesson = Lesson::factory()->create(['file_path' => 'lessons/test.pdf']);

        $this->assertEquals('lessons/test.pdf', $lesson->file_path);
    }
}
