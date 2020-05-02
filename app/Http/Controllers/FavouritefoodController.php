<?php

namespace App\Http\Controllers;

use App\Favouritefood;
use App\Http\Requests\CreateFavouritefoodRequest;
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

    public function show(Favouritefood $favouritefood)
    {
        $favouritefood = auth()->user()->favouritefoods->where('id', '=', $favouritefood->id);

        return Inertia::render('Favouritefood/Index', [
            'favouriteFood' => FavouritefoodResource::collection($favouritefood),
        ]);
    }

    public function store(CreateFavouritefoodRequest $request)
    {
        Favouritefood::create($request->validated());

        return redirect()->route('favouritefoods.index');
    }

    public function delete(Favouritefood $favouritefood)
    {
//        dd('ffuid', $favouritefood->user_id, 'uuid', auth()->user()->id);
        if($favouritefood->user_id == auth()->user()->id) {
            Favouritefood::destroy($favouritefood->id);
        }

        return redirect()->route('favouritefoods.index');
    }
}
