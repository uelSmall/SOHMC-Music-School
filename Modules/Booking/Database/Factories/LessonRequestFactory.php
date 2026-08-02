<?php

namespace Modules\Booking\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Booking\Enums\LessonRequestStatus;
use Modules\Booking\Models\Instrument;
use Modules\Booking\Models\LessonRequest;

class LessonRequestFactory extends Factory
{
    protected $model = LessonRequest::class;

    public function definition(): array
    {
        $requestedDate = $this->faker->dateTimeBetween('+1 week', '+2 months');
        $startHour = $this->faker->numberBetween(8, 18);
        $endHour = min($startHour + $this->faker->numberBetween(1, 2), 21);

        return [
            'student_id' => User::factory(),
            'teacher_id' => User::factory(),
            'instrument_id' => Instrument::factory(),
            'requested_date' => $requestedDate->format('Y-m-d'),
            'requested_start_time' => sprintf('%02d:00:00', $startHour),
            'requested_end_time' => sprintf('%02d:00:00', $endHour),
            'suggested_date' => null,
            'suggested_start_time' => null,
            'suggested_end_time' => null,
            'status' => LessonRequestStatus::Pending,
            'student_note' => $this->faker->optional()->sentence(),
            'teacher_note' => null,
        ];
    }

    public function teacherConfirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonRequestStatus::TeacherConfirmed,
        ]);
    }

    public function teacherRescheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonRequestStatus::TeacherRescheduled,
        ]);
    }

    public function studentAccepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonRequestStatus::StudentAccepted,
        ]);
    }

    public function studentDeclined(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonRequestStatus::StudentDeclined,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => LessonRequestStatus::Cancelled,
        ]);
    }
}