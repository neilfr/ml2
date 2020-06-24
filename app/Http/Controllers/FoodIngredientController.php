<?php

namespace App\Http\Controllers;

use App\Food;
use App\Http\Requests\CreateIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\FoodResource;

class FoodIngredientController extends Controller
{
    public function index(Food $food)
    {
        // dd('hello', auth()->user()->foods()->$food);
        if ($food->user_id === auth()->user()->id) {
            $ingredients = $food->ingredients;
            return Inertia::render('Ingredients/Index', [
                'ingredients' => FoodResource::collection($ingredients),
            ]);
        }
        return redirect()->route('foods.index');
    }

    public function store(CreateIngredientRequest $request, Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            $payload = $request->input();
            $food = Food::find($food->id);
            $food->ingredients()->attach($payload['ingredient_id'], ['quantity' => $payload['quantity']]);
            return redirect()->route('foods.show', $food);
        }
        return redirect()->route('foods.index');
    }

    public function update(UpdateIngredientRequest $request, Food $food, Food $ingredient)
    {
        if ($food->user_id === auth()->user()->id) {
            $payload = $request->input();
            $food->ingredients()->updateExistingPivot($ingredient->id, ['quantity' => $payload['quantity']]);
            return redirect()->route('foods.show', $food);
        }
        return redirect()->route('foods.index');
    }

    public function destroy(Request $request, Food $food, Food $ingredient)
    {
        if ($food->user_id === auth()->user()->id) {
            $food->ingredients()->detach($ingredient);
            return redirect()->route('foods.show', $food);
        }
        return redirect()->route('foods.index');
    }
}
