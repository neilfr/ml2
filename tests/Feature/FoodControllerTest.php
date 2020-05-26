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

        $this->assertEquals($foodgroup->description,$food->foodgroup->description);
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

        $foods = factory(Food::class,2)->create([
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

        $foods = factory(Food::class,2)->create();

        $this->get(route('foods.show', $foods[0]))
            ->assertSuccessful()
            ->assertSee($foods[0]->description)
            ->assertDontSee($foods[1]->description);
    }

    /** @test */
    public function it_cannot_display_a_specific_food_owned_by_another_user()
    {
        $this->withoutExceptionHandling();
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
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $foodgroup = factory(Foodgroup::class)->create();

        $payload = [
            'alias' => 'alias',
            'description' => 'description',
            'kcal' => 123,
            'fat' => 789,
            'protein' => 246,
            'carbohydrate' => 135,
            'potassium' => 456,
            'favourite' => true,
            'source' => 'Health Canada',
            'foodgroup_id' => $foodgroup->id,
            'user_id' => $user->id,
        ];

        $response = $this->post(route('foods.store'), $payload);

        $response->assertRedirect(route('foods.index'));
        $this->assertDatabaseHas('foods',[
            'alias' => 'alias',
            'description' => 'description',
            'kcal' => 123,
            'fat' => 789,
            'protein' => 246,
            'carbohydrate' => 135,
            'potassium' => 456,
            'favourite' => true,
            'source' => 'Health Canada',
            'foodgroup_id' => $foodgroup->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function it_cannot_store_food_if_food_data_is_invalid($getData)
    {
        $this->withoutExceptionHandling();
        [$ruleName, $payload] = $getData();

        $user = factory(User::class)->create();
        $this->actingAs($user);
//        $foodgroup = factory(Foodgroup::class)->create();

//        $payload = [
//            'alias' => 'alias',
//            'description' => 'my food',
//            'kcal' => 'not an integer',
//            'fat' => 789,
//            'protein' => 246,
//            'carbohydrate' => 135,
//            'potassium' => 456,
//            'favourite' => true,
//            'source' => 'Health Canada',
//            'foodgroup_id' => $foodgroup->id,
//            'user_id' => $user->id,
//        ];
dd($payload);
        $response = $this->post(route('foods.store'), $payload);

//        $response->assertRedirect(route('foods.index'));
        $response->assertSessionHasErrors($ruleName);
    }

    public function validationProvider()
    {
        return [
            'it fails if description is null' => [
                function() {
                    return [
                        'description',
                        array_merge($this->getValidData(), ['description' => null]),
                    ];
                }
            ]
        ];
    }

    public function getValidData()
    {
        return[
            'alias' => 'alias',
            'description' => 'my food',
            'kcal' => 123,
            'fat' => 789,
            'protein' => 246,
            'carbohydrate' => 135,
            'potassium' => 456,
            'favourite' => true,
            'source' => 'Health Canada',
            'foodgroup_id' => factory(Foodgroup::class)->create()->pluck('id'),
            'user_id' => auth()->user()->id,
        ];
    }
}
