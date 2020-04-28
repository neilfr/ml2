<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FoodNutrientsController extends Controller
{
    public function index()
    {
        $nutrients = Http::get('https://food-nutrition.canada.ca/api/canadian-nutrient-file/nutrientname/?lang=en&type=json');
        return Inertia::render('Home/Index',
            [
                'nutrients' => $nutrients->json(),
            ]
        );
    }}
