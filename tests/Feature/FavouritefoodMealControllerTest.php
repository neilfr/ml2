<?php

namespace Tests\Feature;

use App\Favouritefood;
use App\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavouritefoodMealControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorizedUserCanOnlySeeTheMealsContainingTheirSelectedFavouritefood()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $mealContainingFavouritefood = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'meal containing the selected favouritefood',
        ]);

        $mealWithoutFavouritefood = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'meal not containing the selected favouritefood',
        ]);

        $selectedFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'my selected favouritefood',
        ]);

        $otherFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'my other favouritefood',
        ]);

        // its this line!!!
        $selectedFavouritefood->meals()->attach($mealContainingFavouritefood->id);
        $otherFavouritefood->meals()->attach($mealWithoutFavouritefood->id);

        $this->get(route('favouritefoodsMeals.index',[
            'favouritefood' => $selectedFavouritefood,
        ]))
            ->assertSuccessful()
            ->assertSee('my selected favouritefood')
            ->assertSee('meal containing the selected favouritefood')
            ->assertDontSee('meal not containing the selected favouritefood');
    }

    /** @test */
    public function AFavouriteFoodThatIsInNoMealsWillReturnWithNoMeals()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $selectedFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'description' => 'my selected favouritefood',
        ]);

        $meal = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'my meal',
        ]);

        $this->get(route('favouritefoodsMeals.index',[
            'favouritefood' => $selectedFavouritefood,
        ]))
            ->assertSuccessful()
            ->assertSee('my selected favouritefood')
            ->assertDontSee('my meal');
    }
}
