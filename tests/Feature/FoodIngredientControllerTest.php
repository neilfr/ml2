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
    public function it_can_add_an_ingredient_to_a_food()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $ingredient = factory(Food::class)->create();

        $payload = [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 200,
        ];

        $response = $this->post(route('food.ingredient.store'), $payload);

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseHas('ingredients', $payload);
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
