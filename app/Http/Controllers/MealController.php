<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealResource;
use App\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    public function index()
    {
        $meals = auth()->user()->meals;

        return Inertia::render('Meals/Index', [
            'meals' => MealResource::collection($meals),
        ]);
    }

    public function show(Meal $meal)
    {
        $meal = auth()->user()->meals->where('id', '=', $meal->id);

        return Inertia::render('Meal/Index', [
            'meal' => MealResource::collection($meal),
//            'meal' => new MealResource($meal),
        ]);
    }
}
