<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\Reservation;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 3);

        return [
            'student_id' => Student::factory(),
            'food_item_id' => FoodItem::factory(),
            'quantity' => $quantity,
            'total_price' => fake()->randomFloat(2, 3, 50),
            'pickup_code' => strtoupper(Str::random(5)),
            'status' => 'pending',
            'pickup_time' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'pickup_time' => fake()->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
