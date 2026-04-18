<?php

namespace Database\Factories;

use App\Models\Cafeteria;
use App\Models\FoodItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoodItem>
 */
class FoodItemFactory extends Factory
{
    public function definition(): array
    {
        $originalPrice = fake()->randomFloat(2, 5, 25);

        return [
            'cafeteria_id' => Cafeteria::factory(),
            'title' => fake()->randomElement([
                'Chicken Rice Bowl', 'Veggie Wrap', 'Pasta Primavera', 'Grilled Sandwich',
                'Caesar Salad', 'Beef Burger', 'Sushi Combo', 'Fried Noodles',
                'Fruit Bowl', 'Soup of the Day', 'Fish & Chips', 'Pizza Slice',
                'Biryani', 'Pad Thai', 'Falafel Plate', 'Ramen Bowl',
            ]),
            'description' => fake()->sentence(10),
            'price' => round($originalPrice * fake()->randomFloat(2, 0.3, 0.7), 2),
            'original_price' => $originalPrice,
            'quantity' => fake()->numberBetween(1, 20),
            'expiry_time' => fake()->dateTimeBetween('+1 day', '+7 days'),
            'image' => null,
            'is_active' => true,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_time' => fake()->dateTimeBetween('-2 hours', '-1 hour'),
            'is_active' => false,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
