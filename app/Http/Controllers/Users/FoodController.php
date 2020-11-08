<?php

namespace App\Http\Controllers\Users;

use App\Food;
use App\User;
use App\Foodgroup;
use FoodgroupSeeder;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodController extends Controller
{

    public function index(Request $request, User $user)
    {
        $foods = $user->foods()->paginate(Config::get('ml2.paginator.per_page'));
        return Inertia::render('Users/Foods/Index',[
            'page' => $foods->currentPage(),
            'foods' => $foods,
            'foodgroups' => Foodgroup::all(),
        ]);
    }

    public function store(Request $request, User $user)
    {
        $food = Food::find($request->input('id'));

        if (($user->id === auth()->user()->id)
            && ($food->foodsource()->first()->sharable
                || $food->user_id === $user->id)){
                $user->foods()->attach($request->input('id'));
            }

        return redirect(route('users.foods.index', $user));
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id !== auth()->user()->id) {
            return redirect(route('users.foods.index', auth()->user()));
        }

        $user->foods()->detach($request->input('id'));
        return redirect(route('users.foods.index', $user));
    }
}
