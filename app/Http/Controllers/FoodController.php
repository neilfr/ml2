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
// revisit with inertia compatible paginator
        //        $foods = Food::paginate(env('PAGINATION_PER_PAGE'));
        $foods = Food::all();

        return Inertia::render('Foods/Index', [
            'foods' => FoodResource::collection($foods),
        ]);
    }

    public function show(Food $food)
    {
        return Inertia::render('Foods/Show', [
            'food' => new FoodResource($food),
        ]);
    }
}
