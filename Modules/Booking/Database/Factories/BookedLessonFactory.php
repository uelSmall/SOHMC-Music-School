<?php

namespace Modules\Booking\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Booking\Enums\LessonStatus;
use Modules\Booking\Models\BookedLesson;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;
use App\Models\User;

class BookedLessonFactory extends Factory
{
    protected $model = BookedLesson::class;

    public function definition(): array
    {
        $lessonDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $startHour = $this->faker->numberBetween(8, 18);
        $endHour = min($startHour + $this->faker->numberBetween(1, 2), 21);

        return [
            'lesson_request_id' => LessonRequest::factory(),
            'student_id' => User::factory(),
            'teacher_id' => User::factory(),
            'instrument_id' => Instrument::factory(),
            'lesson_date' => $lessonDate->format('Y-m-d'),
            'lesson_start_time' => sprintf('%02d:00:00', $startHour),
            'lesson_end_time' => sprintf('%02d:00:00', $endHour),
            'lesson_duration' => 60,
            'status' => LessonStatus::Scheduled,
            'completed_at' => null,
            'cancelled_at' => null,
            'rescheduled_at' => null,
            'cancellation_reason' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (BookedLesson $bookedLesson): void {
            $lessonRequest = $bookedLesson->lessonRequest;

            if (! $lessonRequest) {
                return;
            }

            $bookedLesson->forceFill([
                'student_id' => $lessonRequest->student_id,
                'teacher_id' => $lessonRequest->teacher_id,
                'instrument_id' => $lessonRequest->instrument_id,
                'lesson_duration' => $lessonRequest->lesson_duration,
            ])->saveQuietly();
        });
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }
}