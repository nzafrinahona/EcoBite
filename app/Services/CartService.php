<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\FoodItem;
use App\Models\Student;

class CartService
{
    public function getOrCreateCart(Student $student): Cart
    {
        return Cart::firstOrCreate(['student_id' => $student->id]);
    }

    public function addItem(Cart $cart, int $foodItemId, int $quantity): CartItem
    {
        $foodItem = FoodItem::active()->findOrFail($foodItemId);

        if ($foodItem->quantity < $quantity) {
            throw new \RuntimeException('Insufficient stock available.');
        }

        $existing = $cart->items()->where('food_item_id', $foodItemId)->first();

        if ($existing) {
            $newQty = $existing->quantity + $quantity;
            if ($foodItem->quantity < $newQty) {
                throw new \RuntimeException('Insufficient stock available.');
            }
            $existing->update(['quantity' => $newQty]);
            return $existing->fresh();
        }

        return $cart->items()->create([
            'food_item_id' => $foodItemId,
            'quantity' => $quantity,
        ]);
    }

    public function updateItemQuantity(CartItem $item, int $quantity): CartItem
    {
        $foodItem = $item->foodItem;

        if ($foodItem->quantity < $quantity) {
            throw new \RuntimeException('Insufficient stock available.');
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
