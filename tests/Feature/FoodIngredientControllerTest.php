<?php

namespace Tests\Feature;

use App\Food;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodIngredientControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_a_list_of_ingredients_for_a_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $ingredients = factory(Food::class, 2)->create();

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        foreach ($ingredients as $ingredient) {
            $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);
        }

        $response = $this->get(route('food.ingredient.index', $food));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_cannot_return_a_list_of_ingredients_for_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $ingredients = factory(Food::class, 2)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        foreach ($ingredients as $ingredient) {
            $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);
        }

        $response = $this->get(route('food.ingredient.index', $food));

        $response->assertRedirect(route('foods.index'));
    }

    /** @test */
    public function it_can_add_an_ingredient_to_a_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $ingredient = factory(Food::class)->create();

        $payload = [
            'ingredient_id' => $ingredient->id,
            'quantity' => 200,
        ];

        $response = $this->post(route('food.ingredient.store', $food), $payload);

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseHas('ingredients', $payload);
    }

    /** @test */
    public function it_cannot_add_an_ingredient_to_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        $ingredient = factory(Food::class)->create();

        $payload = [
            'ingredient_id' => $ingredient->id,
            'quantity' => 200,
        ];

        $response = $this->post(route('food.ingredient.store', $food), $payload);

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseMissing('ingredients', $payload);
    }

    /** @test */
    public function ingredients_have_many_parent_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $parentfoods = factory(Food::class, 2)->create();

        $ingredient = factory(Food::class)->create([
            'description' => 'ingredient',
        ]);

        foreach ($parentfoods as $food) {
            $ingredient->parentfoods()->attach($food->id, ['quantity' => 555]);
        }

        foreach ($parentfoods as $food) {
            $this->assertEquals($food->description, $ingredient->parentfoods()->find($food->id)->description);
        }
    }

    /** @test */
    public function ingredients_have_quantities()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $ingredient = factory(Food::class)->create([
            'description' => 'ingredient',
        ]);

        $parentfood = factory(Food::class)->create([
            'description' => 'parentfood',
        ]);

        $parentfood->ingredients()->attach($ingredient->id, ['quantity' => 555]);

        // dd($parentfood->ingredients()->first()->pivot->quantity);

        $this->assertDatabaseHas('ingredients', [
            'parent_food_id' => $parentfood->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 555,
        ]);
    }
}
