<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Active Listing Dashboard
     */
    public function index()
    {
        // Get all food items for the dashboard
        $foodItems = FoodItem::all();
        
        // Calculate the stats for the dashboard
        $stats = [
            'total'        => $foodItems->count(),
            'in_stock'     => $foodItems->where('stock', '>', 0)->count(),
            'out_of_stock' => $foodItems->where('stock', '<=', 0)->count(),
        ];

        $mostPopularItem = FoodItem::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->first();

        return view('food_items.index', compact('foodItems', 'stats', 'mostPopularItem'));
    }

    // (Other methods like create, store, edit, update, destroy would stay here)
    public function create() { return view('food_items.create'); }
    public function store(Request $request) { /* Logic already implemented */ }
    public function edit(FoodItem $foodItem) { return view('food_items.edit', compact('foodItem')); }
    public function update(Request $request, FoodItem $foodItem) { /* Logic already implemented */ }
    public function destroy(FoodItem $foodItem) { $foodItem->delete(); return redirect()->back(); }
    public function studentFeed(Request $request) 
    { 
        $query = FoodItem::where('stock', '>', 0);

        // Feature: Price Sort
        if ($request->has('sort')) {
            $sortOrder = ($request->sort == 'desc') ? 'desc' : 'asc';
            $query->orderBy('discounted_price', $sortOrder);
        }

        $foodItems = $query->get();
        return view('food_items.student_feed', compact('foodItems')); 
    }
}
