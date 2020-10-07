<?php

namespace App\Http\Controllers\Api\Foods;

use App\Food;
use App\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateIngredientRequest;
use Illuminate\Http\Response;

class IngredientController extends Controller
{
    public function update(UpdateIngredientRequest $request, Food $food, Food $ingredient)
    {
        dd($food->id, $ingredient->id, $request->query());
        if ($food->user_id === auth()->user()->id) {
            $payload = $request->input();
            $food->ingredients()->updateExistingPivot($ingredient->id, ['quantity' => $payload['quantity']]);
            return Response::HTTP_OK;
        }
        return Response::HTTP_OK;
    }
}
