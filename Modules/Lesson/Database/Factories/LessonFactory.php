<?php

namespace Modules\Lesson\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Lesson\Enums\LessonStatus;
use Modules\Lesson\Models\Lesson;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->slug(),
            'content' => $this->faker->paragraphs(3, true),
            'description' => $this->faker->sentence(),
            'status' => LessonStatus::Draft,
            'published_at' => null,
            'order' => $this->faker->numberBetween(1, 100),
            'teacher_id' => User::factory(),
            'file_path' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Published,
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Archived,
            'published_at' => null,
        ]);
    }

    public function withFile(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_path' => 'lessons/' . $this->faker->uuid() . '.pdf',
        ]);
    }
}
