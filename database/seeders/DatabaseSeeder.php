<?php

namespace Database\Seeders;

use App\Models\Cafeteria;
use App\Models\CafeteriaStaff;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\FoodItem;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Cafeterias ──
        $cafeterias = Cafeteria::factory(3)->create();

        // ── Staff Users + Profiles ──
        $staffUser = User::factory()->staff()->create([
            'name' => 'Staff Admin',
            'email' => 'staff@ecobite.com',
        ]);

        CafeteriaStaff::factory()->create([
            'user_id' => $staffUser->id,
            'cafeteria_id' => $cafeterias[0]->id,
            'position' => 'Manager',
        ]);

        // Additional staff
        foreach ($cafeterias as $cafeteria) {
            $staffUsers = User::factory(2)->staff()->create();
            foreach ($staffUsers as $su) {
                CafeteriaStaff::factory()->create([
                    'user_id' => $su->id,
                    'cafeteria_id' => $cafeteria->id,
                ]);
            }
        }

        // ── Student Users + Profiles ──
        $studentUser = User::factory()->student()->create([
            'name' => 'Test Student',
            'email' => 'student@ecobite.com',
        ]);

        $mainStudent = Student::factory()->create([
            'user_id' => $studentUser->id,
            'student_id' => 'STU00001',
            'department' => 'Computer Science',
        ]);

        $students = [];
        $studentUsers = User::factory(9)->student()->create();
        foreach ($studentUsers as $su) {
            $students[] = Student::factory()->create(['user_id' => $su->id]);
        }
        $students[] = $mainStudent;

        // ── Food Items ──
        $allFoodItems = collect();
        foreach ($cafeterias as $cafeteria) {
            $items = FoodItem::factory(8)->create(['cafeteria_id' => $cafeteria->id]);
            $allFoodItems = $allFoodItems->merge($items);

            // A couple expired items
            FoodItem::factory(2)->expired()->create(['cafeteria_id' => $cafeteria->id]);
        }

        // ── Carts + Cart Items ──
        foreach (array_slice($students, 0, 5) as $student) {
            $cart = Cart::create(['student_id' => $student->id]);
            $randomItems = $allFoodItems->random(rand(1, 3));
            foreach ($randomItems as $foodItem) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'food_item_id' => $foodItem->id,
                    'quantity' => rand(1, 2),
                ]);
            }
        }

        // ── Reservations ──
        $reservations = [];
        foreach ($students as $student) {
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $foodItem = $allFoodItems->random();
                $qty = rand(1, 2);

                $reservations[] = Reservation::create([
                    'student_id' => $student->id,
                    'food_item_id' => $foodItem->id,
                    'quantity' => $qty,
                    'total_price' => $foodItem->price * $qty,
                    'pickup_code' => strtoupper(Str::random(5)),
                    'status' => fake()->randomElement(['pending', 'completed', 'completed', 'cancelled']),
                    'pickup_time' => fake()->optional(0.5)->dateTimeBetween('-3 days', 'now'),
                ]);
            }
        }

        // ── Reviews (for completed reservations) ──
        $completedReservations = collect($reservations)->where('status', 'completed');
        foreach ($completedReservations->take(10) as $reservation) {
            Review::create([
                'reservation_id' => $reservation->id,
                'rating' => rand(1, 5),
                'comment' => fake()->optional(0.7)->sentence(10),
            ]);
        }

        // ── Notifications ──
        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->user_id,
                'title' => 'Welcome to EcoBite!',
                'message' => 'Start exploring surplus food deals from campus cafeterias.',
                'type' => 'info',
            ]);
        }
    }
}
