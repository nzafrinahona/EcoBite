<?php

namespace Database\Factories;

use App\Models\Cafeteria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cafeteria>
 */
class CafeteriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Cafeteria',
            'location' => fake()->randomElement(['Building A', 'Building B', 'Building C', 'Student Center', 'Library Wing']),
            'operating_hours' => fake()->randomElement(['8:00 AM - 8:00 PM', '7:00 AM - 10:00 PM', '9:00 AM - 6:00 PM']),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
