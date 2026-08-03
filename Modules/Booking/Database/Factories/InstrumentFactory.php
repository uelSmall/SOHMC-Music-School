<?php

namespace Modules\Booking\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Booking\Models\Instrument;

class InstrumentFactory extends Factory
{
    protected $model = Instrument::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Piano',
                'Guitar',
                'Vocals',
                'Percussion',
                'Violin',
                'Drums',
                'Steel Pan',
            ]),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}