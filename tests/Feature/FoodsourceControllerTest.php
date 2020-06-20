<?php

namespace Tests\Feature;

use App\Food;
use App\User;
use App\Foodsource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodsourceControllerTest extends TestCase
{
    use RefreshDatabase;

    // public function it_has_a_foodsource()
    // {
    //     $user = factory(User::class)->create();
    //     $this->actingAs($user);

    //     $food
    // }

    /** @test */
    public function it_has_many_foods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodsource = factory(Foodsource::class)->create();

        $foods = factory(Food::class, 5)->create([
            'foodsource_id' => $foodsource->id,
        ]);

        $this->assertCount(5, $foodsource->foods()->get());
    }
}
