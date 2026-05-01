<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * FR-11: Show Cart (Temporary)
     */
    public function showCart()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        if ($cart) {
            $ids = array_keys($cart);
            $items = FoodItem::whereIn('id', $ids)->get()->map(function($item) use ($cart) {
                $item->qty = $cart[$item->id]['quantity'];
                return $item;
            });

            foreach ($items as $item) {
                $total += $item->qty * $item->discounted_price;
            }
        }

        return view('food_items.cart', compact('items', 'total'));
    }

    /**
     * FR-11: Add to Cart
     */
    public function addToCart(Request $request, $id)
    {
        $foodItem = FoodItem::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "title" => $foodItem->title,
                "quantity" => 1,
                "price" => $foodItem->discounted_price,
                "photo" => $foodItem->photo
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    /**
     * FR-12: Confirm Reservation
     * Instantly deducts from stock and generates a 5-digit code (FR-13).
     */
    public function confirm(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('student-feed')->with('error', 'Cart is empty!');
        }

        foreach ($cart as $id => $details) {
            $foodItem = FoodItem::find($id);
            if (!$foodItem || $foodItem->stock < $details['quantity']) {
                return redirect()->back()->with('error', "Not enough stock for {$details['title']}");
            }

            // Deduct stock (FR-12)
            $foodItem->decrement('stock', $details['quantity']);

            // Create Reservation
            Reservation::create([
                'user_id'      => Auth::id(),
                'food_item_id' => $id,
                'quantity'     => $details['quantity'],
                'pickup_code'  => strtoupper(Str::random(5)), // FR-13: Unique 5-char code
                'status'       => 'confirmed',
            ]);
        }

        session()->forget('cart');
        return redirect()->route('reservations.history')->with('success', 'Reservation confirmed! Items reserved.');
    }

    /**
     * FR-14: Order History View
     */
    public function history()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('foodItem')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('food_items.history', compact('reservations'));
    }

    /**
     * FR-15: Cancel Reservation
     * Restores stock and marks as cancelled.
     */
    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        if ($reservation->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed reservations can be cancelled.');
        }

        // Restore stock (FR-15)
        $foodItem = $reservation->foodItem;
        $foodItem->increment('stock', $reservation->quantity);

        // Update status
        $reservation->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Reservation cancelled and stock restored!');
    }

    /**
     * Show Pickup Code Verification Form
     */
    public function showVerifyForm()
    {
        return view('reservations.verify');
    }

    /**
     * Process Pickup Code Verification
     */
    public function processVerify(Request $request)
    {
        $request->validate([
            'pickup_code' => 'required|string|size:5'
        ]);

        $reservation = Reservation::where('pickup_code', strtoupper($request->pickup_code))->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Invalid pickup code.');
        }

        if ($reservation->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Reservation is already ' . $reservation->status . '.');
        }

        $reservation->update(['status' => 'picked_up']);

        return redirect()->back()->with('success', 'Pickup code verified successfully! Reservation marked as completed.');
    }
}
