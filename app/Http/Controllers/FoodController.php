<?php

namespace App\Http\Controllers;

use App\Food;
use App\Http\Requests\CreateFoodRequest;
use App\Http\Resources\FoodResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Integer;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::where('user_id', auth()->user()->id)->get();

        return Inertia::render('Foods/Index', [
            'foods' => FoodResource::collection($foods),
        ]);
    }

    public function store(CreateFoodRequest $request)
    {
        Food::create($request->validated());

        return redirect()->route('foods.index');
    }

    public function show(Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            return Inertia::render('Foods/Show', [
                'food' => new FoodResource($food),
            ]);
        } else {
            return redirect()->route('foods.index');
        }
    }

    public function update(Request $request, Food $food)
    {
        // dd($request->input());
        $food->update($request->input());

        return redirect(route('foods.index'));
    }
}
