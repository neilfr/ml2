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
use Illuminate\Support\Facades\Config;
use App\Http\Requests\CreateFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodgroupResource;
use App\Http\Resources\IngredientResource;
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
        ->favouritesFilter($request->query('favouritesFilter'))
        ->paginate(Config::get('ml2.paginator.per_page'));

        $foodgroups = Foodgroup::all();

        return Inertia::render('Foods/Index', [
            'page' => $foods->currentPage(),
            'foods' => FoodResource::collection($foods),
            'foodgroups' => FoodgroupResource::collection($foodgroups),
        ]);
    }

    public function create()
    {
        return Inertia::render('Foods/Create');
    }

    public function store(CreateFoodRequest $request)
    {
        Food::create($request->validated());

        return redirect()->route('foods.index');
    }

    public function show(Request $request, Food $food)
    {
        $foodgroups = Foodgroup::all();
        $foods = Food::userFoods()
            ->sharedFoods()
            ->foodgroupSearch($request->query('foodgroupSearch'))
            ->descriptionSearch($request->query('descriptionSearch'))
            ->aliasSearch($request->query('aliasSearch'))
            ->with('ingredients')
            ->paginate(Config::get('ml2.paginator.per_page'));

        if (($food->user_id === auth()->user()->id) || ($food->foodsource->sharable === true)){
            return Inertia::render('Foods/Show', [
                'food' => new FoodResource($food),
                'foods' => FoodResource::collection($foods),
                'foodgroups' => FoodgroupResource::collection($foodgroups),
            ]);
        }
        return redirect()->route('foods.index');
    }

    public function update(UpdateFoodRequest $request, Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            $food->update($request->validated());
        }
        // return  redirect()->back();
        return  redirect(route('foods.index'));

    }

    public function destroy(Food $food)
    {
        if ($food->user_id === auth()->user()->id) {
            Food::destroy($food->id);
        }

        return redirect()->route('foods.index');
    }
}
