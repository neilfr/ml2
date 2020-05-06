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
//        $favouritefoods = auth()->user()->favouritefoods;
        $favouritefoods = Favouritefood::where('user_id',auth()->user()->id)
            ->orderBy('alias','ASC')
            ->orderBy('description','ASC')
            ->get();
        return Inertia::render('Favouritefood/Index', [
            'favouritefoods' => FavouritefoodResource::collection($favouritefoods),
        ]);
    }

    public function show(Favouritefood $favouritefood)
    {
        if($favouritefood->user_id === auth()->user()->id) {
            return Inertia::render('Favouritefood/Index', [
                'favouritefood' => new FavouritefoodResource($favouritefood),
            ]);
        }
        return redirect()->route('favouritefoods.index');

    }

    public function store(CreateFavouritefoodRequest $request)
    {
        Favouritefood::create($request->validated());

        return redirect()->route('favouritefoods.index');
    }

    public function delete(Favouritefood $favouritefood)
    {
        if($favouritefood->user_id === auth()->user()->id) {
            Favouritefood::destroy($favouritefood->id);
        }

        return redirect()->route('favouritefoods.index');
    }
}
