<?php

namespace App\Http\Controllers;

use App\Food;
use App\Http\Resources\FoodResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Integer;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::where('user_id', auth()->user()->id)->get();

        return Inertia::render('Foods/Index', [
            'foods' => FoodResource::collection($foods),
        ]);
    }

    public function show(Food $food)
    {
        if($food->user_id === auth()->user()->id){
            return Inertia::render('Foods/Show', [
                'food' => new FoodResource($food),
            ]);
        } else {
            return redirect()->route('foods.index');
        }
    }
}
