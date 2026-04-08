<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodItemRequest;
use App\Http\Requests\UpdateFoodItemRequest;
use App\Models\FoodItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(): View
    {
        $foodItems = FoodItem::with('cafeteria')
            ->active()
            ->latest()
            ->paginate(12);

        return view('foods.index', compact('foodItems'));
    }

    public function create(): View
    {
        return view('foods.create');
    }

    public function store(StoreFoodItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('food-images', 'public');
        }

        $staff = $request->user()->staffProfile;
        $data['cafeteria_id'] = $staff->cafeteria_id;

        FoodItem::create($data);

        return redirect()->route('staff.food-items.index')
            ->with('success', 'Food item added successfully!');
    }

    public function show(FoodItem $foodItem): View
    {
        $foodItem->load('cafeteria');
        return view('foods.show', compact('foodItem'));
    }

    public function edit(FoodItem $foodItem): View
    {
        return view('foods.edit', compact('foodItem'));
    }

    public function update(UpdateFoodItemRequest $request, FoodItem $foodItem): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $data['image'] = $request->file('image')->store('food-images', 'public');
        }

        $foodItem->update($data);

        return redirect()->route('staff.food-items.index')
            ->with('success', 'Food item updated successfully!');
    }

    public function destroy(FoodItem $foodItem): RedirectResponse
    {
        if ($foodItem->image) {
            Storage::disk('public')->delete($foodItem->image);
        }

        $foodItem->delete();

        return redirect()->route('staff.food-items.index')
            ->with('success', 'Food item deleted successfully!');
    }
} 