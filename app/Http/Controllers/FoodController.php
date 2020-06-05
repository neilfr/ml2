<?php

namespace App\Http\Controllers;

use App\Food;
use App\Foodsource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\FoodResource;
use App\Http\Requests\CreateFoodRequest;
use phpDocumentor\Reflection\Types\Integer;

class FoodController extends Controller
{
    public function index()
    {
        $sharableFoodsources = Foodsource::where('sharable', '=', true);
        dd($sharableFoodsources);

        // $foods = Food::where('user_id', auth()->user()->id)
        //     ->orWhere('foodsource_id', '=', $sharableFoodsource->id)
        //     ->get();

        // dd($sharableFoodsourceId, $foods);

        //$foods2 = foods->foodsource->sharable===true;

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
        if ($food->foodsource->updatable) {
            $food->update($request->input());
        }

        return redirect(route('foods.index'));
    }
}
