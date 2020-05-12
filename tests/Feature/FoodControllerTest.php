<?php

namespace Tests\Feature;

use App\Food;
use App\Foodgroup;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorizedUserCanAccessFood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->get(route('foods.index'))->assertSuccessful();
    }

    /** @test */
    public function anUnauthorizedUserCannotAccessFood()
    {
        $this->get(route('foods.index'))->assertRedirect('/login');
    }

    /** @test */
    public function anAuthorizedUserCanSeeFoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class,2)->create();

        $this->get(route('foods.index'))
            ->assertSuccessful()
            ->assertSee($foods[0]->description)
            ->assertSee($foods[1]->description);
    }

    /** @test */
    public function anAuthorizedUserCanSeePaginatedFoods()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class,env('PAGINATION_PER_PAGE')+5)->create();

        $this->get(route('foods.index'))
            ->assertSuccessful()
            ->assertSee($foods[0]->description)
            ->assertDontSee($foods[env('PAGINATION_PER_PAGE')]->description);
    }

    /** @test */
    public function anAuthorizedUserCanSeeASpecificFood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class,2)->create();

        $this->get(route('foods.show', $foods[0]))
            ->assertSuccessful()
            ->assertSee($foods[0]->description);
    }

    /** @test */
    public function aFoodHasAFoodgroup()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroup = factory(Foodgroup::class)->create();

        $food = factory(Food::class)->create([
            "foodgroup_id" => $foodgroup->id,
        ]);

        $this->assertEquals($foodgroup->description,$food->foodgroup->description);
    }

}
