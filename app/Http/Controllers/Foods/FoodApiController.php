<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FoodApiController extends Controller
{
    public function index()
    {
        $foods = Http::get('https://food-nutrition.canada.ca/api/canadian-nutrient-file/food/?lang=en&type=json')->json();
//        dd($foods);
//        $nutrientAmounts = Http::get('https://food-nutrition.canada.ca/api/canadian-nutrient-file/nutrientamount/?lang=en&type=json')->json();
//        dd($nutrientAmounts);
//        $potassiumAmounts = array_filter($nutrientAmounts, function($nutrientAmount) {
//            return ($nutrientAmount["nutrient_name_id"] == 306);
//        }) ;
//        dd($potassiumAmounts);

//        $foodsWithNutrients = array_map(function($food){
//            $food["newkey"] = "newvalue";
//            return $food;
//        }, $foods);
//        dd($foodsWithNutrients);
//        dd($nutrients->json());
//        dd($foods);
        return Inertia::render('FoodsApi/Index',
            [
                'foods' => $foods,
            ]
        );
    }}
