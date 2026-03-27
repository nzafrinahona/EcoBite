<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * View cart page
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('food')
            ->get();

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->food->discounted_price);

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * FR-11: Add item to cart
     */
    public function add(Request $request, $foodId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $food = Food::findOrFail($foodId);
        $quantity = (int) $request->quantity;

        if ($quantity > $food->stock) {
            return back()->with('error', 'Only ' . $food->stock . ' items available in stock.');
        }

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('food_id', $foodId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $food->stock) {
                return back()->with('error', 'Cannot add more — only ' . $food->stock . ' in stock and you already have ' . $cartItem->quantity . ' in cart.');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id'  => auth()->id(),
                'food_id'  => $foodId,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', '"' . $food->title . '" added to your cart!');
    }

    /**
     * Remove one item from cart
     */
    public function remove($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Cart cleared.');
    }
}
