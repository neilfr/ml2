<?php

namespace Tests\Feature;

use App\Favouritefood;
use App\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MealControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorizedUserCanCreateAMeal()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'description' => 'my meal',
        ];

        $this->post(route('meals.store'), $payload)
        ->assertRedirect(route('meals.index'));

        $this->assertDatabaseHas('meals', $payload);
    }

    /** @test */
    public function anAuthorizedUserCannotCreateSomeoneElsesMeal()
    {
        $thisUser = factory(User::class)->create();
        $this->actingAs($thisUser);

        $anotherUser = factory(User::class)->create();

        $payload = [
            'user_id' => $anotherUser->id,
            'description' => 'my meal',
        ];

        $this->post(route('meals.store'), $payload)
            ->assertRedirect(route('meals.index'));
        $this->assertDatabaseMissing('meals', $payload);
    }

    /** @test */
    public function aUserCanHaveManyMeals()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal1 = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "my meal",
        ]);
        $meal2 = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "my other meal",
        ]);

        $this->assertTrue(auth()->user()->meals->contains($meal1));
        $this->assertTrue(auth()->user()->meals->contains($meal2));
    }

    /** @test */
    public function aMealHasAUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "my meal",
        ]);

        $this->assertEquals($user->name, $meal->user->name);
    }

    /** @test */
    public function anAuthorizedUserCanAccessTheirMeal()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal = factory(Meal::class)->create([
            'user_id' => $user->id,
            'description' => 'my meal',
        ]);

        $this->get(route('meals.index'))
            ->assertSuccessful()
            ->assertSee($meal->description);
    }

    /** @test */
    public function anAuthorizedUserCannotSeeSomeoneElsesMeal()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $someoneElse = factory(User::class)->create();
        $meal = factory(Meal::class)->create([
            "user_id" => $someoneElse->id,
            "description" => "someone elses meal",
        ]);

        $this->get(route('meals.index'))
            ->assertSuccessful()
            ->assertDontSee($meal->description);
    }

    /** @test */
    public function anAuthorizedUserCanAccessTheirOwnSpecificMeal()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "my meal",
        ]);

        $this->get(route('meals.show',[
            "meal" => $meal,
        ]))
            ->assertSuccessful()
            ->assertSee($meal->description);
    }

    /** @test */
    public function anAuthorizedUserCannotSeeSomeoneElsesSpecificMeal()
    {
        $userA = factory(User::class)->create();
        $this->actingAs($userA);

        $userB = factory(User::class)->create();

        $meal = factory(Meal::class)->create([
            "user_id" => $userB->id,
            "description" => "UserB meal",
        ]);

        $this->get(route('meals.show',[
            'user' => $userA,
            'meal' => $meal,
        ]))
            ->assertSuccessful()
            ->assertDontSee($meal->description);
    }

    /** @test */
    public function aMealCanHaveManyFavouriteFoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal = factory(Meal::class)->create([
            'user_id' => $user->id,
        ]);

        $favouritefoods = factory(Favouritefood::class,2)->create([
            'user_id' => $user->id,
        ]);

        foreach ($favouritefoods as $favouritefood){
            $meal->favouritefoods()->attach($favouritefood->id);
        }

        $this->assertDatabaseHas('favouritefood_meal',[
            'favouritefood_id' => 1,
            'meal_id' => 1,
        ])->assertDatabaseHas('favouritefood_meal',[
            'favouritefood_id' => 2,
            'meal_id' => 1,
        ]);
    }

    /** @test */
    public function aUsersMealsAreOrderedByDescription()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meal1 = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "z is my last meal",
        ]);
        $meal2 = factory(Meal::class)->create([
            "user_id" => $user->id,
            "description" => "a is my first meal",
        ]);

        $this->get(route('meals.index'))
            ->assertSeeInOrder([
                $meal2->description,
                $meal1->description,
            ]);
        }

}
