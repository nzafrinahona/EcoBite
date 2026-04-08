<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'student_id' => fake()->unique()->numerify('STU#####'),
            'department' => fake()->randomElement(['Computer Science', 'Engineering', 'Business', 'Arts', 'Science', 'Medicine']),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
