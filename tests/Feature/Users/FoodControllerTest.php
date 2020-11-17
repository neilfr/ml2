<?php

namespace Tests\Feature\Users;

use App\Food;
use App\User;
use App\Foodsource;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_a_users_food_list_as_authorized_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('users.foods.index', $user))->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_cannot_access_a_users_food_list_as_unauthorized_user()
    {
        $user = factory(User::class)->create();
        $this->get(route('users.foods.index', $user))->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_have_a_list_of_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class, 2)->create();

        foreach($foods as $food){
            $user->foods()->attach($food->id);
        }

        $this->assertEquals(2, $user->foods()->count());

        foreach ($foods as $food) {
            $this->assertDatabaseHas('food_user', [
                'user_id' => $user->id,
                'food_id' => $food->id,
            ]);
        }
    }

    /** @test */
    public function it_can_return_a_users_list_of_foods_as_a_paginated_list_with_foodgroups()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class,2)->create();

        foreach($foods as $food){
            $user->foods()->attach($food);
        }

        $response = $this->get(route('users.foods.index', $user))
            ->assertOk()
            ->assertHasProp('foodgroups')
            ->assertPropValue('foods', function ($returnedFoods) use ($foods) {
                $this->assertArrayHasKey('per_page', $returnedFoods['meta']);
                foreach ($returnedFoods['data'] as $index => $food) {
                    $this->assertEquals($food['description'], $foods->toArray()[$index]['description']);
                }
            });

    }

    /** @test */
    public function it_can_add_a_food_to_their_own_user_food_list()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $response = $this->post(route('users.foods.store', $user), $food->toArray())
            ->assertRedirect(route('users.foods.index', $user));

        $this->assertDatabaseHas('food_user', [
            'food_id' => $food->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_add_another_users_shared_food_to_their_own_user_food_list()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();
        $foodsource = factory(Foodsource::class)->create([
            'sharable' => true,
        ]);

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->post(route('users.foods.store', $user), $food->toArray())
            ->assertRedirect(route('users.foods.index', $user));

        $this->assertDatabaseHas('food_user', [
            'food_id' => $food->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_cannot_add_another_users_food_to_their_user_food_list_if_it_is_not_sharable()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();
        $foodsource = factory(Foodsource::class)->create([
            'sharable' => false,
        ]);

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->post(route('users.foods.store', $user), $food->toArray())
            ->assertRedirect(route('users.foods.index', $user));

        $this->assertDatabaseMissing('food_user', [
            'food_id' => $food->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_remove_a_food_from_the_users_food_list()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $user->foods()->attach($food->id);

        $this->assertDatabaseHas('food_user', [
            'food_id' => $food->id,
            'user_id' => $user->id,
        ]);

        $response = $this->delete(route('users.foods.destroy', $user), $food->toArray())
            ->assertRedirect(route('users.foods.index', $user));

        $this->assertDatabaseMissing('food_user', [
            'food_id' => $food->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_cannot_remove_a_food_from_another_users_food_list()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create();

        $anotherUser->foods()->attach($food->id);

        $this->assertDatabaseHas('food_user', [
            'food_id' => $food->id,
            'user_id' => $anotherUser->id,
        ]);

        $response = $this->delete(route('users.foods.destroy', $anotherUser), $food->toArray())
            ->assertRedirect(route('users.foods.index', $user));

        $this->assertDatabaseHas('food_user', [
            'food_id' => $food->id,
            'user_id' => $anotherUser->id,
        ]);
    }

}
