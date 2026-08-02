<?php

namespace Modules\Booking\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Booking\Models\TeacherAvailability;

class TeacherAvailabilityFactory extends Factory
{
    protected $model = TeacherAvailability::class;

    public function definition(): array
    {
        $startHour = $this->faker->numberBetween(8, 16);
        $endHour = min($startHour + $this->faker->numberBetween(1, 4), 20);

        return [
            'teacher_id' => User::factory(),
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', $endHour),
            'is_available' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}