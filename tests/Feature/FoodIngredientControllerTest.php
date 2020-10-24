<?php

namespace Tests\Feature;

use App\Food;
use App\Http\Resources\IngredientResource;
use App\Ingredient;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodIngredientControllerTest extends TestCase
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

        $this->assertDatabaseHas('ingredients', [
            'parent_food_id' => $parentfood->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 555,
        ]);
    }


    /** @test */
    public function it_can_return_a_list_of_ingredients_with_quantities_for_a_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $ingredients = factory(Ingredient::class, 2)->create([
            'base_quantity' => 333,
        ]);

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        foreach ($ingredients as $ingredient) {
            $food->ingredients()->attach($ingredient->id, ['quantity' => 555]);
        }

        $response = $this->get(route('food.ingredient.index', $food));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertPropValue('ingredients', function ($returnedIngredients) use ($ingredients) {
            $this->assertCount(2, $returnedIngredients['data']);
            foreach($returnedIngredients['data'] as $index => $returnedIngredient){
                $this->assertEquals($returnedIngredient['description'], $ingredients->toArray()[$index]['description']);
                $this->assertEquals($returnedIngredient['base_quantity'], $ingredients->toArray()[$index]['base_quantity']);
                $this->assertEquals($returnedIngredient['quantity'], 555);
            }
        });
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
    public function it_cannot_add_the_same_ingredient_to_a_user_owned_food_more_than_once()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $ingredient = factory(Food::class)->create();

        $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);

        $payload = [
            'ingredient_id' => $ingredient->id,
            'quantity' => 200,
        ];

        $response = $this->post(route('food.ingredient.store', $food), $payload);

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseMissing('ingredients', $payload);
    }

    /** @test */
    public function it_will_have_an_ingredient_with_quantity_of_ingredients_base_quantity_if_not_provided()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $ingredient = factory(Food::class)->create([
            'base_quantity' => 999,
        ]);

        $payload = [
            'ingredient_id' => $ingredient->id,
        ];
        $expectedResult = array_merge($payload, ['quantity' => 999]);

        $response = $this->post(route('food.ingredient.store', $food), $payload);

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseHas('ingredients', $expectedResult);
    }

    /**
     * @test
     * @dataProvider ingredientStoreValidationProvider
     *  */
    public function it_cannot_store_ingredient_if_ingredient_data_is_invalid($getData)
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        [$ruleName, $payload] = $getData();
        // dd($payload);
        $response = $this->post(route('food.ingredient.store', $payload['parent_food_id']), $payload);

        $response->assertSessionHasErrors($ruleName);
    }

    /** @test */
    public function it_cannot_update_ingredient_if_ingredient_data_is_invalid()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $ingredient = factory(Food::class)->create();
        $food = factory(Food::class)->create();
        $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);

        $payload = [
            'ingredient_id' => $ingredient->id,
            'quantity' => 'not an integer',
        ];

        $response = $this->patch(route('food.ingredient.update', [
            'food' => $food,
            'ingredient' => $ingredient,
        ]), $payload);

        $response->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function it_can_update_an_ingredients_quantity_for_a_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'description' => 'parent food',
            'user_id' => $user->id,
        ]);

        $ingredient = factory(Food::class)->create([
            'description' => 'ingredient',
        ]);

        $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);

        $payload = [
            'quantity' => 999,
        ];

        $response = $this->patch(route('food.ingredient.update', [
            'food' => $food,
            'ingredient' => $ingredient,
        ]), $payload);

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseHas('ingredients', [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => $payload['quantity'],
        ]);
    }

    /** @test */
    public function it_cannot_update_an_ingredients_quantity_for_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        $ingredient = factory(Food::class)->create();

        $food->ingredients()->attach($ingredient->id, ['quantity' => 100]);

        $payload = [
            'quantity' => 200,
        ];

        $response = $this->patch(route('food.ingredient.update', [
            'food' => $food,
            'ingredient' => $ingredient,
        ]), $payload);

        $response->assertRedirect(route('foods.index'));

        $this->assertDatabaseMissing('ingredients', [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => $payload['quantity'],
        ]);
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
    public function it_can_remove_an_ingredient_from_a_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();
$foods=factory(Food::class,10)->create();
        $ingredients = factory(Ingredient::class,2)->create();

        foreach($ingredients as $ingredient) {
            $food->ingredients()->attach($ingredient->id, ['quantity' => 555]);
        }

        $response = $this->delete(route('food.ingredient.destroy', [
            'food' => $food,
            'ingredient' =>$ingredients[0],
        ]));

        $response->assertRedirect(route('foods.show', $food));
        $this->assertDatabaseMissing('ingredients', [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredients[0]->id,
            'quantity' => 555,
        ]);
    }

    /** @test */
    public function it_cannot_remove_an_ingredient_from_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        $ingredient = factory(Food::class)->create();

        $food->ingredients()->attach($ingredient->id, ['quantity' => 555]);

        $response = $this->delete(route('food.ingredient.destroy', [
            'food' => $food,
            'ingredient' => $ingredient,
        ]));

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseHas('ingredients', [
            'parent_food_id' => $food->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 555,
        ]);
    }

    public function ingredientStoreValidationProvider()
    {
        return [
            'it fails if quantity is not an integer' => [
                function () {
                    return [
                        'quantity',
                        array_merge($this->getValidIngredientData(), ['quantity' => 'not an integer']),
                    ];
                }
            ],
            'it fails if ingredient_id is not an integer' => [
                function () {
                    return [
                        'ingredient_id',
                        array_merge($this->getValidIngredientData(), ['ingredient_id' => 'not an integer']),
                    ];
                }
            ],
            'it fails if ingredient_id is not a valid food id' => [
                function () {
                    return [
                        'ingredient_id',
                        array_merge($this->getValidIngredientData(), ['ingredient_id' => 99999]),
                    ];
                }
            ]
        ];
    }

    public function getValidIngredientData()
    {
        return [
            'parent_food_id' => factory(Food::class)->create()->id,
            'ingredient_id' => factory(Food::class)->create()->id,
            'quantity' => 100,
        ];
    }
}
