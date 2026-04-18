<?php

namespace App\Services;

use App\Models\FoodItem;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationService
{
    public function createReservation(Student $student, int $foodItemId, int $quantity): Reservation
    {
        return DB::transaction(function () use ($student, $foodItemId, $quantity) {
            $foodItem = FoodItem::lockForUpdate()->findOrFail($foodItemId);

            if (!$foodItem->is_active || $foodItem->expiry_time->isPast()) {
                throw new \RuntimeException('This food item is no longer available.');
            }

            if ($foodItem->quantity < $quantity) {
                throw new \RuntimeException('Insufficient stock. Only ' . $foodItem->quantity . ' left.');
            }

            $foodItem->decrement('quantity', $quantity);

            if ($foodItem->quantity === 0) {
                $foodItem->update(['is_active' => false]);
            }

            $reservation = Reservation::create([
                'student_id' => $student->id,
                'food_item_id' => $foodItemId,
                'quantity' => $quantity,
                'total_price' => $foodItem->price * $quantity,
                'pickup_code' => $this->generatePickupCode(),
                'status' => 'pending',
            ]);

            Notification::create([
                'user_id' => $student->user_id,
                'title' => 'Reservation Confirmed',
                'message' => "Your reservation for {$foodItem->title} (x{$quantity}) is confirmed. Pickup code: {$reservation->pickup_code}",
                'type' => 'reservation',
            ]);

            return $reservation;
        });
    }

    public function cancelReservation(Reservation $reservation): Reservation
    {
        if (!$reservation->isPending()) {
            throw new \RuntimeException('Only pending reservations can be cancelled.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'cancelled']);

            $foodItem = FoodItem::lockForUpdate()->find($reservation->food_item_id);
            if ($foodItem) {
                $foodItem->increment('quantity', $reservation->quantity);
                if (!$foodItem->is_active && $foodItem->quantity > 0 && $foodItem->expiry_time->isFuture()) {
                    $foodItem->update(['is_active' => true]);
                }
            }

            Notification::create([
                'user_id' => $reservation->student->user_id,
                'title' => 'Reservation Cancelled',
                'message' => "Your reservation #{$reservation->id} has been cancelled. Stock has been restored.",
                'type' => 'reservation',
            ]);

            return $reservation->fresh();
        });
    }

    public function completeReservation(Reservation $reservation): Reservation
    {
        if (!$reservation->isPending()) {
            throw new \RuntimeException('Only pending reservations can be completed.');
        }

        return DB::transaction(function () use ($reservation) {
            $reservation->update([
                'status' => 'completed',
                'pickup_time' => now(),
            ]);

            Notification::create([
                'user_id' => $reservation->student->user_id,
                'title' => 'Pickup Completed',
                'message' => "Your pickup for reservation #{$reservation->id} is complete. Enjoy your meal!",
                'type' => 'pickup',
            ]);

            return $reservation->fresh();
        });
    }

    public function verifyPickupCode(string $code): ?Reservation
    {
        return Reservation::with(['student.user', 'foodItem'])
            ->where('pickup_code', $code)
            ->where('status', 'pending')
            ->first();
    }

    private function generatePickupCode(): string
    {
        do {
            $code = strtoupper(Str::random(5));
        } while (Reservation::where('pickup_code', $code)->exists());

        return $code;
    }
}
