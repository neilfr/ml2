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
    public function update(Request $request, Food $food, Ingredient $ingredient)
    {
        if ($food->user_id !== auth()->user()->id){
            return Response::HTTP_FORBIDDEN;
        }
        $payload = $request->input();
        $food->ingredients()->updateExistingPivot($ingredient->id, ['quantity' => $payload['quantity']]);
        return Response::HTTP_OK;
    }
}
