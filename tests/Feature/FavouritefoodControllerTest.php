<?php

namespace Tests\Feature;

use App\Favouritefood;
use App\Meal;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavouritefoodControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aUserCanHaveManyFavouritefoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood1 = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "description" => "my favourite food",
        ]);
        $favouritefood2 = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "description" => "my other favourite food",
        ]);

        $this->assertTrue(auth()->user()->favouritefoods->contains($favouritefood1));
        $this->assertTrue(auth()->user()->favouritefoods->contains($favouritefood2));
    }

    /** @test */
    public function aFavouritefoodHasAUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "description" => "my favourite food",
        ]);

        $this->assertEquals($user->name, $favouritefood->user->name);
    }

    /** @test */
    public function anAuthorizedUserCanAccessTheirOwnFavouritefood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "code" => '2',
            "alias" => 'my alias',
            "description" => "my favourite food",
            "kcal" => 119,
            "potassium" => 204,
        ]);

        $this->get(route('favouritefoods.index'))
            ->assertSuccessful()
            ->assertSee($favouritefood->code)
            ->assertSee($favouritefood->alias)
            ->assertSee($favouritefood->description)
            ->assertSee($favouritefood->kcal)
            ->assertSee($favouritefood->potassium);
    }

    /** @test */
    public function anAuthorizedUserCannotSeeSomeoneElsesFavouritefoods()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $someoneElse = factory(User::class)->create();
        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $someoneElse->id,
            "description" => "someone elses food",
        ]);

        $this->get(route('favouritefoods.index'))
            ->assertSuccessful()
            ->assertDontSee($favouritefood->description);
    }

    /** @test */
    public function anAuthorizedUserCanAccessTheirOwnSpecificFavouritefood()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "description" => "my favourite food",
        ]);

        $this->get(route('favouritefoods.show',[
            'favouritefood' => $favouritefood,
        ]))
            ->assertSuccessful()
            ->assertSee($favouritefood->description);
    }

    /** @test */
    public function anAuthorizedUserCannotSeeSomeoneElsesSpecificFavouritefood()
    {
        $userA = factory(User::class)->create();
        $this->actingAs($userA);

        $userB = factory(User::class)->create();

        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $userB->id,
            "description" => "UserB favourite food",
        ]);

        $this->get(route('favouritefoods.show',[
            'user' => $userA,
            'favouritefood' => $favouritefood,
        ]))
            ->assertSuccessful()
            ->assertDontSee($favouritefood->description);
    }

    /** @test */
    public function aFavouritefoodCanBeInManyMeals()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $meals = factory(Meal::class, 2)->create([
            'user_id' => $user->id,
        ]);

        $favouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
        ]);

        foreach ($meals as $meal){
            $favouritefood->meals()->attach($meal->id);
        }

        $this->assertDatabaseHas('favouritefood_meal',[
            'favouritefood_id' => 1,
            'meal_id' => 1,
        ])->assertDatabaseHas('favouritefood_meal',[
            'favouritefood_id' => 1,
            'meal_id' => 2,
        ]);
    }

    /** @test */
    public function anAuthorizedUserCanCreateAFavouritefood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = [
            "user_id" => $user->id,
            "alias" => 'my alias',
            "description" => "my favourite food",
            "kcal" => 119,
            "potassium" => 204,
        ];

        $response = $this->post(route('favouritefoods.index',$favouritefood));

        $this->assertDatabaseHas('favouritefoods',$favouritefood);

        $response->assertRedirect(route('favouritefoods.index'));
    }

    /** @test */
    public function aFavouritefoodMustHaveADescription()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = [
            "user_id" => $user->id,
            "code" => '2',
            "alias" => 'my alias',
            "description" => "",
            "kcal" => 119,
            "potassium" => 204,
        ];

        $response = $this->post(route('favouritefoods.index',$favouritefood));

        $response->assertSessionHasErrors(['description']);
    }

    /** @test */
    public function aFavouritefoodKcalMustBeANonnegativeInteger()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = [
            "user_id" => $user->id,
            "code" => '2',
            "alias" => 'my alias',
            "description" => "my favourite food",
            "kcal" => -1,
            "potassium" => 204,
        ];

        $response = $this->post(route('favouritefoods.index',$favouritefood));

        $response->assertSessionHasErrors(['kcal']);
    }

    /** @test */
    public function aFavouritefoodPotassiumMustBeANonnegativeInteger()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = [
            "user_id" => $user->id,
            "code" => '2',
            "alias" => 'my alias',
            "description" => "my favourite food",
            "kcal" => 119,
            "potassium" => -1,
        ];

        $response = $this->post(route('favouritefoods.index',$favouritefood));

        $response->assertSessionHasErrors(['potassium']);
    }
}
