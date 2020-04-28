<?php

namespace Tests\Feature;

use App\Food;
use App\Foodgroup;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodgroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anAuthorizedUserCanAccessFoodgroups()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->get(route('foodgroups.index'))->assertSuccessful();
    }

    /** @test */
    public function anUnauthorizedUserCannotAccessFoodgroups()
    {
        $this->get(route('foodgroups.index'))->assertRedirect('/login');
    }

    /** @test */
    public function anAuthorizedUserCanSeeFoodgroups()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroups = factory(Foodgroup::class,2)->create();

        $this->get(route('foodgroups.index'))
            ->assertSuccessful()
            ->assertSee($foodgroups[0]->description)
            ->assertSee($foodgroups[1]->description);
    }

    /** @test */
    public function anAuthorizedUserCanSeeASpecificFoodgroup()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroups = factory(Foodgroup::class,2)->create();

        $this->get(route('foodgroups.show', $foodgroups[0]))
            ->assertSuccessful()
            ->assertSee($foodgroups[0]->description);
    }

    /** @test */
    public function aFoodgroupCanHaveManyFoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroup = factory(Foodgroup::class)->create();
        $foods = factory(Food::class,2)->create([
            'foodgroup_id' => $foodgroup->id,
        ]);

        $this->assertTrue($foodgroup->foods->contains($foods[0]));
        $this->assertTrue($foodgroup->foods->contains($foods[1]));
    }

}
