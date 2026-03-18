<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    /**
     * Active Listing Dashboard (Staff)
     */
    public function index()
    {
        $foodItems = FoodItem::all();

        $stats = [
            'total'        => $foodItems->count(),
            'in_stock'     => $foodItems->where('stock', '>', 0)->count(),
            'out_of_stock' => $foodItems->where('stock', '<=', 0)->count(),
        ];

        return view('food_items.index', compact('foodItems', 'stats'));
    }

    public function create()
    {
        return view('food_items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:100',
            'description'      => 'nullable|string|max:500',
            'photo'            => 'nullable|image|max:2048',
            'standard_price'   => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0|lte:standard_price',
            'stock'            => 'required|integer|min:1',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('foods', 'public');
        }

        FoodItem::create($validated);

        return redirect()->back()->with('success', 'Food item added successfully.');
    }

    public function edit(FoodItem $foodItem)
    {
        return view('food_items.edit', compact('foodItem'));
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:100',
            'description'      => 'nullable|string|max:500',
            'photo'            => 'nullable|image|max:2048',
            'standard_price'   => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0|lte:standard_price',
            'stock'            => 'required|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('foods', 'public');
        }

        $foodItem->update($validated);

        return redirect()->back()->with('success', 'Food item updated successfully.');
    }

    public function destroy(FoodItem $foodItem)
    {
        $foodItem->delete();
        return redirect()->back()->with('success', 'Food item deleted.');
    }

    /**
     * Student Feed — FR-05, FR-10 (Sort by price)
     */
    public function studentFeed(Request $request)
    {
        $query = FoodItem::where('stock', '>', 0);

        // FR-10: Sort by price — default to ascending
        $sort = $request->get('sort', 'asc');

        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderBy('discounted_price', $sort);
        } else {
            $query->orderBy('discounted_price', 'asc');
        }

        $foodItems = $query->get();

        return view('food_items.student_feed', compact('foodItems', 'sort'));
    }
}
