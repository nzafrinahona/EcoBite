<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index(): View
    {
        $student = auth()->user()->student;
        $cart = $this->cartService->getOrCreateCart($student);
        $cart->load('items.foodItem.cafeteria');

        return view('student.cart', compact('cart'));
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $student = auth()->user()->student;
        $cart = $this->cartService->getOrCreateCart($student);

        try {
            $this->cartService->addItem(
                $cart,
                $request->validated('food_item_id'),
                $request->validated('quantity')
            );
            return back()->with('success', 'Item added to cart!');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        try {
            $this->cartService->updateItemQuantity($cartItem, $request->quantity);
            return back()->with('success', 'Cart updated!');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->removeItem($cartItem);
        return back()->with('success', 'Item removed from cart!');
    }

    public function clear(): RedirectResponse
    {
        $student = auth()->user()->student;
        $cart = $this->cartService->getOrCreateCart($student);
        $this->cartService->clearCart($cart);

        return back()->with('success', 'Cart cleared!');
    }
}
