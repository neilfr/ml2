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
use App\Http\Resources\FoodgroupResource;
use App\Http\Resources\User\FoodResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodController extends Controller
{

    public function index(Request $request, User $user)
    {
        $foods = $user->foods()->paginate(Config::get('ml2.paginator.per_page'));
        return Inertia::render('Users/Foods/Index',[
            'user' => $user,
            'page' => $foods->currentPage(),
            'foods' => FoodResource::collection($foods),
            'foodgroups' => FoodgroupResource::collection(Foodgroup::all()),
        ]);
    }

    public function show(Request $request, User $user, Food $food)
    {
        // $foods = Food::userFoods()
        //     ->sharedFoods()
        //     ->foodgroupSearch($request->query('foodgroupSearch'))
        //     ->descriptionSearch($request->query('descriptionSearch'))
        //     ->aliasSearch($request->query('aliasSearch'))
        //     ->favouritesFilter($request->query('favouritesFilter'))
        //     ->with('ingredients')
        //     ->paginate(Config::get('ml
        $foods = $user->foods()->with('ingredients')->paginate(Config::get('ml2.paginator.per_page'));

        if (($food->user_id === auth()->user()->id) || ($food->foodsource->sharable === true)){
            return Inertia::render('Users/Foods/Show', [
                'user' => $user,
                'food' => new FoodResource($food),
                'foods' => FoodResource::collection($foods),
                'foodgroups' => FoodgroupResource::collection(Foodgroup::all()),
            ]);
        }
        return redirect()->route('foods.index');
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
