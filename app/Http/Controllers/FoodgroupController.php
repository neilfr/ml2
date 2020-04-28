<?php

namespace App\Http\Controllers;

use App\Foodgroup;
use App\Http\Resources\FoodgroupResource;
use App\Http\Resources\FoodResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FoodgroupController extends Controller
{
    public function index()
    {
        $foodgroups = Foodgroup::All();
        return Inertia::render('Foodgroups/Index', [
            'foodGroups' => FoodgroupResource::collection($foodgroups),
        ]);
    }

    public function show(Foodgroup $foodgroup)
    {
        return Inertia::render('Foodgroups/Show', [
            'foodgroup' => new FoodgroupResource($foodgroup),
        ]);
    }
}
