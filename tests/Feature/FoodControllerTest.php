<?php

namespace Tests\Feature;

use App\Food;
use App\User;
use App\Foodgroup;
use App\Foodsource;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodControllerTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function it_can_access_foods_as_authorized_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('foods.index'))->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_cannot_access_food_as_unauthorized_user()
    {
        $this->get(route('foods.index'))->assertRedirect('/login');
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $food->user->id);
    }

    /** @test */
    public function it_belongs_to_a_foodgroup()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroup = factory(Foodgroup::class)->create();
        $food = factory(Food::class)->create([
            "foodgroup_id" => $foodgroup->id,
        ]);

        $this->assertEquals($foodgroup->description, $food->foodgroup->description);
    }

    /** @test */
    public function it_belongs_to_a_foodsource()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodsource = factory(Foodsource::class)->create();
        $food = factory(Food::class)->create([
            "foodsource_id" => $foodsource->id,
        ]);

        $this->assertEquals($foodsource->name, $food->foodsource->name);
    }

    /** @test */
    public function it_has_many_ingredients()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $ingredients = factory(Food::class, 2)->create();

        $parentfood = factory(Food::class)->create([
            'description' => 'parentfood',
        ]);

        foreach ($ingredients as $ingredient) {
            $parentfood->ingredients()->attach($ingredient->id, ['quantity' => 100]);
        }

        foreach ($ingredients as $food) {
            $this->assertEquals($food->description, $parentfood->ingredients()->find($food->id)->description);
        }
    }

    /** @test */
    public function it_can_return_user_owned_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class, 2)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('foods.index'))
            ->assertStatus(Response::HTTP_OK);

        $response->assertPropValue('foods', function ($returnedFoods) use ($foods) {
            $this->assertCount(2, $returnedFoods['data']);
            $this->assertEquals($returnedFoods['data'], $foods->toArray());
        });
    }

    /** @test */
    public function it_can_return_other_users_shared_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();
        $foodsource = factory(Foodsource::class)->create([
            'sharable' => true,
        ]);

        $foods = factory(Food::class, 2)->create([
            'user_id' => $anotherUser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->get(route('foods.index'))
            ->assertStatus(Response::HTTP_OK);

        $response->assertPropValue('foods', function ($returnedFoods) use ($foods) {
            $this->assertCount(2, $returnedFoods['data']);
            $this->assertEquals($returnedFoods['data'], $foods->toArray());
        });
    }

    /** @test */
    public function it_cannot_return_other_users_foods_that_are_not_sharable()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $this->actingAs($user);

        $foodsource = factory(Foodsource::class)->create([
            'sharable' => false,
        ]);

        $foods = factory(Food::class, 2)->create([
            'user_id' => $otherUser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->get(route('foods.index'));

        $response->assertPropValue('foods', function ($returnedFoods) use ($foods) {
            $this->assertCount(0, $returnedFoods['data']);
        });
    }

    /** @test */
    public function it_can_return_a_specific_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class, 2)->create([
            'favourite' => true,
        ]);

        $response = $this->get(route('foods.show', $foods[0]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertPropValue('food', function ($returnedFood) use ($foods) {
                $this->assertCount(14, $returnedFood['data']);
                $this->assertEquals($returnedFood['data'], $foods[0]->toArray());
            });
    }

    /** @test */
    public function it_can_return_a_specific_food_owned_by_another_user_if_the_food_is_sharable()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $otheruser = factory(User::class)->create();

        $foodsource = factory(Foodsource::class)->create([
            'sharable' => true,
        ]);

        $food = factory(Food::class)->create([
            'user_id' => $otheruser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->get(route('foods.show', $food));

        $response->assertStatus(Response::HTTP_OK)
            ->assertPropValue('food', function ($returnedFood) use ($food) {
                $this->assertCount(14, $returnedFood['data']);
                $this->assertEquals($returnedFood['data'], $food->toArray());
            });
    }

    /** @test */
    public function it_cannot_return_a_specific_food_owned_by_another_user_that_is_not_sharable()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $otheruser = factory(User::class)->create();

        $foodsource = factory(Foodsource::class)->create([
            'sharable' => false,
        ]);

        $food = factory(Food::class)->create([
            'user_id' => $otheruser->id,
            'foodsource_id' => $foodsource->id,
        ]);

        $response = $this->get(route('foods.show', $food));

        $response->assertRedirect(route('foods.index'));
    }

    /** @test */
    public function it_can_store_a_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $payload = $this->getValidFoodData();
        $response = $this->post(route('foods.store'), $payload);

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseHas('foods', $payload);
    }

    /**
     * @test
     * @dataProvider foodStoreValidationProvider
     */
    public function it_cannot_store_food_if_food_data_is_invalid($getData)
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        [$ruleName, $payload] = $getData();

        $response = $this->post(route('foods.store'), $payload);

        $response->assertSessionHasErrors($ruleName);
    }

    /**
     * @test
     * @dataProvider foodUpdateValidationProvider
     */
    public function it_cannot_update_food_if_food_data_is_invalid($getData)
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        [$ruleName, $payload] = $getData();

        $response = $this->patch(route('foods.update', $food->id), $payload);

        $response->assertSessionHasErrors($ruleName);
    }

    /** @test */
    public function it_can_update_a_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        $payload = [
            'description' => 'new description',
        ];

        $response = $this->patch(route('foods.update', $food->id), $payload);

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseHas('foods', $payload);
    }

    /** @test */
    public function it_cannot_update_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        $payload = [
            'description' => 'new description'
        ];

        $response = $this->patch(route('foods.update', $food->id), $payload);

        $this->assertDatabaseMissing('foods', $payload);
        $response->assertRedirect(route('foods.index'));
    }

    /** @test */
    public function it_can_delete_a_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete(route('foods.destroy', $food->id))
            ->assertRedirect(route('foods.index'));

        $response = $this->assertDatabaseMissing('foods', ['id' => $food->id]);
    }

    /** @test */
    public function it_cannot_delete_another_users_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $anotherUser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $anotherUser->id,
        ]);

        $response = $this->delete(route('foods.destroy', $food->id))
            ->assertRedirect(route('foods.index'));

        $response = $this->assertDatabaseHas('foods', ['id' => $food->id]);
    }

    public function foodStoreValidationProvider()
    {
        return [
            'it fails if alias is not a string' => [
                function () {
                    return [
                        'alias',
                        array_merge($this->getValidFoodData(), ['alias' => []]),
                    ];
                }
            ],
            'it fails if description is not a non-empty string' => [
                function () {
                    return [
                        'description',
                        array_merge($this->getValidFoodData(), ['description' => '']),
                    ];
                }
            ],
            'it fails if kcal is not an integer' => [
                function () {
                    return [
                        'kcal',
                        array_merge($this->getValidFoodData(), ['kcal' => 'not an integer']),
                    ];
                }
            ],
            'it fails if fat is not an integer' => [
                function () {
                    return [
                        'fat',
                        array_merge($this->getValidFoodData(), ['fat' => 'not an integer']),
                    ];
                }
            ],
            'it fails if protein is not an integer' => [
                function () {
                    return [
                        'protein',
                        array_merge($this->getValidFoodData(), ['protein' => 'not an integer']),
                    ];
                }
            ],
            'it fails if carbohydrate is not an integer' => [
                function () {
                    return [
                        'carbohydrate',
                        array_merge($this->getValidFoodData(), ['carbohydrate' => 'not an integer']),
                    ];
                }
            ],
            'it fails if potassium is not an integer' => [
                function () {
                    return [
                        'potassium',
                        array_merge($this->getValidFoodData(), ['potassium' => 'not an integer']),
                    ];
                }
            ],
            'it fails if favourite is not a boolean' => [
                function () {
                    return [
                        'favourite',
                        array_merge($this->getValidFoodData(), ['favourite' => 'not a boolean']),
                    ];
                }
            ],
            'it fails if foodgroup_id is not a valid foodgroup id' => [
                function () {
                    return [
                        'foodgroup_id',
                        array_merge($this->getValidFoodData(), ['foodgroup_id' => 99999999]),
                    ];
                }
            ],
            'it fails if user_id is not a valid user id' => [
                function () {
                    return [
                        'user_id',
                        array_merge($this->getValidFoodData(), ['user_id' => 99999999]),
                    ];
                }
            ]
        ];
    }

    public function foodUpdateValidationProvider()
    {
        return [
            'it fails if alias is not a string' => [
                function () {
                    return [
                        'alias', ['alias' => []],
                    ];
                }
            ],
            'it fails if description is not a non-empty string' => [
                function () {
                    return [
                        'description', ['description' => ''],
                    ];
                }
            ],
            'it fails if kcal is not an integer' => [
                function () {
                    return [
                        'kcal', ['kcal' => 'not an integer'],
                    ];
                }
            ],
            'it fails if fat is not an integer' => [
                function () {
                    return [
                        'fat', ['fat' => 'not an integer'],
                    ];
                }
            ],
            'it fails if protein is not an integer' => [
                function () {
                    return [
                        'protein', ['protein' => 'not an integer'],
                    ];
                }
            ],
            'it fails if carbohydrate is not an integer' => [
                function () {
                    return [
                        'carbohydrate', ['carbohydrate' => 'not an integer'],
                    ];
                }
            ],
            'it fails if potassium is not an integer' => [
                function () {
                    return [
                        'potassium', ['potassium' => 'not an integer'],
                    ];
                }
            ],
            'it fails if favourite is not a boolean' => [
                function () {
                    return [
                        'favourite', ['favourite' => 'not a boolean'],
                    ];
                }
            ],
            'it fails if foodgroup_id is not a valid foodgroup id' => [
                function () {
                    return [
                        'foodgroup_id', ['foodgroup_id' => 99999999],
                    ];
                }
            ],
            'it fails if user_id is not a valid user id' => [
                function () {
                    return [
                        'user_id', ['user_id' => 99999999],
                    ];
                }
            ]
        ];
    }

    public function getValidFoodData()
    {
        return [
            'alias' => 'alias',
            'description' => 'my food',
            'kcal' => 123,
            'fat' => 789,
            'protein' => 246,
            'carbohydrate' => 135,
            'potassium' => 456,
            'favourite' => true,
            'foodgroup_id' => factory(Foodgroup::class)->create()->id,
            'foodsource_id' => factory(Foodsource::class)->create()->id,
            'user_id' => auth()->user()->id,
        ];
    }
}
