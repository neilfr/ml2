<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouritefoodResource;
use App\Http\Resources\MealResource;
use App\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealFavouritefoodController extends Controller
{
    public function index(Meal $meal){

        $mealWithFavouritefoods = auth()->user()
            ->meals()
            ->where('id', '=', $meal->id)
            ->with('favouritefoods')->get();

        return Inertia::render('Meal/Favouritefood/Index', [
            'meal' => MealResource::collection($mealWithFavouritefoods),
        ]);

//        $favouritefoods = auth()->user()->favouritefoods;
//
//        return Inertia::render('Meal/Favouritefood/Index', [
//            'favouriteFoods' => FavouritefoodResource::collection($favouritefoods),
//        ]);
    }
}
