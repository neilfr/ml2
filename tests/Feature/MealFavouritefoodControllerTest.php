<?php

namespace Tests\Feature;

use App\Favouritefood;
use App\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MealFavouritefoodControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorizedUserCanOnlySeeTheFavouritefoodsInTheirSelecttedMeal()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $selectedMeal = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'my selected meal',
        ]);
        $otherMeal = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'my other meal',
        ]);

        $favouritefoodInSelectedMeal = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'favouritefood in the selected meal',
        ]);

        $favouritefoodNotInSelectedMeal = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'favouritefood not in the selected meal',
        ]);

        $selectedMeal->favouritefoods()->attach($favouritefoodInSelectedMeal->id);
        $otherMeal->favouritefoods()->attach($favouritefoodNotInSelectedMeal->id);
        $this->get(route('mealsFavouritefoods.index',[
            'meal' => $selectedMeal,
        ]))
            ->assertSuccessful()
            ->assertSee('my selected meal')
            ->assertSee('favouritefood in the selected meal')
            ->assertDontSee('favouritefood not in the selected meal');
    }

    /** @test */
    public function AMealWithNoFavouriteFoodsWillReturnWithNoFavouriteFoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $selectedMeal = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'my selected meal',
        ]);

        $favouritefood = factory(favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'my favouritefood',
        ]);

        $this->get(route('mealsFavouritefoods.index',[
            'meal' => $selectedMeal,
        ]))
            ->assertSuccessful()
            ->assertSee('my selected meal')
            ->assertDontSee('my favouritefood');
    }
}
