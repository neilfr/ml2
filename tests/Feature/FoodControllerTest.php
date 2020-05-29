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
    public function it_can_access_foods_as_authorized_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->get(route('foods.index'))->assertSuccessful();
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


    //    /** @test */
    //    public function it_belongs_to_many_meals()
    //    {
    //
    //    }

    /** @test */
    public function it_can_display_user_owned_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class, 2)->create([
            'user_id' => $user->id,
        ]);

        $this->get(route('foods.index'))
            ->assertSuccessful()
            ->assertSee($foods[0]->description)
            ->assertSee($foods[1]->description);
    }

    /** @test */
    public function it_cannot_display_foods_owned_by_other_users()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create([
            'user_id' => $otherUser->id,
        ]);

        $this->get(route('foods.index'))
            ->assertSuccessful()
            ->assertDontSee($food->description);
    }

    /** @test */
    public function it_can_display_a_specific_user_owned_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foods = factory(Food::class, 2)->create();

        $this->get(route('foods.show', $foods[0]))
            ->assertSuccessful()
            ->assertSee($foods[0]->description)
            ->assertDontSee($foods[1]->description);
    }

    /** @test */
    public function it_cannot_display_a_specific_food_owned_by_another_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $otheruser = factory(User::class)->create();

        $food = factory(Food::class)->create([
            'user_id' => $otheruser->id,
        ]);

        $this->get(route('foods.show', $food))
            ->assertRedirect(route('foods.index'));
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
     * @dataProvider foodValidationProvider
     */
    public function it_cannot_store_food_if_food_data_is_invalid($getData)
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        [$ruleName, $payload] = $getData();

        $response = $this->post(route('foods.store'), $payload);

        $response->assertSessionHasErrors($ruleName);
    }

    /** @test */
    public function it_can_update_a_food()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $food = factory(Food::class)->create();

        $payload = $food->toArray();
        $payload['description'] = 'new description';

        $response = $this->withoutExceptionHandling()->patch(route('foods.update', $food->id), $payload);

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseHas('foods', $payload);
    }

    public function foodValidationProvider()
    {
        return [
            'it fails if description is not a string' => [
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
            'it fails if source is not a non-empty string' => [
                function () {
                    return [
                        'source',
                        array_merge($this->getValidFoodData(), ['source' => '']),
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
            'source' => 'Health Canada',
            'foodgroup_id' => factory(Foodgroup::class)->create()->id,
            'user_id' => auth()->user()->id,
        ];
    }
}
