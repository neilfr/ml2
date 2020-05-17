<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMealRequest;
use App\Http\Resources\MealResource;
use App\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::where('user_id', auth()->user()->id)
            ->orderBy('description', 'ASC')
            ->get();

        return Inertia::render('Meals/Index', [
            'meals' => MealResource::collection($meals),
        ]);
    }

    public function show(Meal $meal)
    {
        $meal = auth()->user()->meals->where('id', '=', $meal->id);

        return Inertia::render('Meal/Index', [
            'meal' => MealResource::collection($meal),
        ]);
    }

    public function store(CreateMealRequest $request)
    {
        $meal = $request->validated();
        if($meal['user_id'] === auth()->user()->id){
            Meal::create($meal);
        }

        return redirect(route('meals.index'));
    }
}
