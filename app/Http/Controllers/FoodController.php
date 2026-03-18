<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FoodController extends Controller
{

public function index()
{
    $foods = \App\Models\Food::all();
    return view('foods.index', compact('foods'));
} 


public function create()
{
    return view('foods.create');
}


public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'expiry_time' => 'required',
        'cafeteria_name' => 'required',
    ]);

    Food::create($request->all());

    return redirect()->route('foods.index')->with('success', 'Food added!');
}


    public function show(Food $food)
    {
        //
    }


public function edit($id)
{
    $food = \App\Models\Food::findOrFail($id);
    return view('foods.edit', compact('food'));
}

public function update(Request $request, $id)
{
    $food = \App\Models\Food::findOrFail($id);

    $food->update($request->only([
    'title', 'description', 'price', 'quantity', 'expiry_time', 'cafeteria_name'
]));

    return redirect('/foods')->with('success', 'Food updated successfully!');
}


public function destroy($id)
{
    $food = \App\Models\Food::findOrFail($id);
    $food->delete();

    return redirect('/foods')->with('success', 'Food deleted!');
}

} 