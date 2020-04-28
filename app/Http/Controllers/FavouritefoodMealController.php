<?php

namespace App\Http\Controllers;

use App\Favouritefood;
use App\Http\Resources\FavouritefoodResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavouritefoodMealController extends Controller
{
    public function index(Favouritefood $favouritefood){

        $favouritefoodWithMeals = auth()->user()
            ->favouritefoods()
            ->where('id','=',$favouritefood->id)
            ->with('meals')->get();

        return Inertia::render('Favouritefood/Meal/Index', [
            'favouritefood' => FavouritefoodResource::collection($favouritefoodWithMeals),
        ]);
    }
}
