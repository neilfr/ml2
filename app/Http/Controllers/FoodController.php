<?php

namespace App\Http\Controllers;

use App\Food;
use App\Foodgroup;
use App\Foodsource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\FoodResource;
use App\Http\Requests\CreateFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodgroupResource;
use phpDocumentor\Reflection\Types\Integer;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $foods = Food::userFoods()
        ->sharedFoods()
        ->foodgroupSearch($request->query('foodgroupSearch'))
        ->descriptionSearch($request->query('descriptionSearch'))
        ->aliasSearch($request->query('aliasSearch'))
        ->get();

        $foodgroups = Foodgroup::all();

        return Inertia::render('Foods/Index', [
            'foods' => FoodResource::collection($foods),
            'foodgroups' => FoodgroupResource::collection($foodgroups)
        ]);
    }

    public function store(CreateFoodRequest $request)
    {
        Food::create($request->validated());

        return redirect()->route('foods.index');
    }

    public function show(Food $food)
    {
        if (($food->user_id === auth()->user()->id) || ($food->foodsource->sharable === true)) {
            return Inertia::render('Foods/Show', [
                'food' => new FoodResource($food),
            ]);
        } else {
            return redirect()->route('foods.index');
        }
    }

    public function update(UpdateFoodRequest $request, Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            $food->update($request->input());
        }
        return redirect(route('foods.index'));
    }

    public function destroy(Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            Food::destroy($food->id);
        }

        return redirect()->route('foods.index');
    }
}
