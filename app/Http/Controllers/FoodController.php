<?php

namespace App\Http\Controllers;

use App\Food;
use App\Http\Resources\FoodResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Integer;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::All();

        return Inertia::render('Foods/Index', [
            'foods' => FoodResource::collection($foods),
        ]);
    }

    public function show(Food $food)
    {
        return Inertia::render('Foods/Show', [
            'food' => new FoodResource($food),
        ]);
    }
}
