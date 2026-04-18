<?php

namespace Database\Factories;

use App\Models\Cafeteria;
use App\Models\CafeteriaStaff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CafeteriaStaff>
 */
class CafeteriaStaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->staff(),
            'cafeteria_id' => Cafeteria::factory(),
            'position' => fake()->randomElement(['Manager', 'Cook', 'Server', 'Cashier']),
        ];
    }
}
