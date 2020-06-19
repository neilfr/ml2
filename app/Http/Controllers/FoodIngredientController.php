<?php

namespace App\Http\Controllers;

use App\Food;
use Illuminate\Http\Request;

class FoodIngredientController extends Controller
{
    public function store(Request $request)
    {
        $payload = $request->input();
        $food = Food::find($payload['parent_food_id']);
        $food->ingredients()->attach($payload['ingredient_id'], ['quantity' => $payload['quantity']]);
        return redirect()->route('foods.show', $food);
    }
}
