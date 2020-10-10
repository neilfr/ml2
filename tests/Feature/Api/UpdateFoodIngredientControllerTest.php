<?php

namespace Tests\Feature\Api;

use App\Food;
use App\Ingredient;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFoodIngredientControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_an_ingredient()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
                'description' => 'parent food',
                'user_id' => $user->id,
            ]);

        $ingredient = factory(Ingredient::class)->create([
            'description' => 'ingredient',
        ]);


        $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);

        $payload = [
            'quantity' => 200,
        ];

        $response = $this->patch(route('api.foods.ingredients.update', [
            'food' => $food,
            'ingredient' => $ingredient,
        ]), $payload);
// dd('stop');
        $this->assertDatabaseHas('ingredients', [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => $payload['quantity'],
        ]);
    }
}
