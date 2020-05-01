<?php

namespace App\Http\Controllers;

use App\Favouritefood;
use App\Http\Requests\CreateFavouritefoodRequest;
use App\Http\Requests\UpdateFavouritefoodRequest;
use App\Http\Resources\FavouritefoodResource;
use App\Http\Resources\FoodResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FavouritefoodController extends Controller
{
    public function index()
    {
        $favouritefoods = auth()->user()->favouritefoods;

        return Inertia::render('Favouritefood/Index', [
            'favouriteFoods' => FavouritefoodResource::collection($favouritefoods),
        ]);
    }

    public function store(CreateFavouritefoodRequest $request)
    {
        Favouritefood::create($request->validated());

        return redirect()->route('favouritefoods.index');
    }

    public function show(Favouritefood $favouritefood)
    {
        if($favouritefood->user_id === auth()->user()->id) {
            return Inertia::render('Favouritefood/Index', [
                'favouriteFood' => new FavouritefoodResource($favouritefood),
            ]);
        } else {
            return redirect()->route('favouritefoods.index');
        }
    }

    public function update(UpdateFavouritefoodRequest $request, Favouritefood $favouritefood)
    {
        $favouritefood->update($request->validated());

        return redirect()->route('favouritefoods.index');
    }

}
