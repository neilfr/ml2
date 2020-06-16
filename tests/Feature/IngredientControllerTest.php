<?php

namespace Tests\Feature;

use App\Food;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientControllerTest extends TestCase
{
    use RefreshDatabase;

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
