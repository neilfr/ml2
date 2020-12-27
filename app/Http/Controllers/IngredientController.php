<?php

namespace App\Http\Controllers;

use App\Food;
use App\Foodgroup;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\FoodgroupResource;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $foods = Food::query()
        ->userFoods()
        ->sharedFoods()
        ->foodgroupSearch($request->query('foodgroupSearch'))
        ->descriptionSearch($request->query('descriptionSearch'))
        ->aliasSearch($request->query('aliasSearch'))
        ->favouritesFilter($request->query('favouritesFilter'))
        ->paginate(Config::get('ml2.paginator.per_page'));

        $foodgroups = Foodgroup::all();

        return Inertia::render('Ingredients/Index', [
            'page' => $foods->currentPage(),
            'foods' => FoodResource::collection($foods),
            'foodgroups' => FoodgroupResource::collection($foodgroups),
        ]);
    }
}
