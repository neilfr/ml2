<?php

namespace Tests\Feature;

use App\Favouritefood;
use App\Foodgroup;
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
        $this->assertDatabaseHas('favouritefoods', ['description' => $favouritefood1->description]);
        $this->assertDatabaseHas('favouritefoods', ['description' => $favouritefood2->description]);

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
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = factory(Favouritefood::class)->create([
            "user_id" => $user->id,
            "description" => "my favourite food",
        ]);

        $this->get(route('favouritefoods.show', [
            'favouritefood' => $favouritefood,
        ]))
            ->assertSuccessful()
            ->assertSee($favouritefood->code)
            ->assertSee($favouritefood->alias)
            ->assertSee($favouritefood->description)
            ->assertSee($favouritefood->kcal)
            ->assertSee($favouritefood->potassium);
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

        $response = $this->get(route('favouritefoods.show', [
            'user' => $userB,
            'favouritefood' => $favouritefood,
        ]))
            ->assertRedirect(route('favouritefoods.index'))
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

        foreach ($meals as $meal) {
            $favouritefood->meals()->attach($meal->id);
        }

        $this->assertDatabaseHas('favouritefood_meal', [
            'favouritefood_id' => 1,
            'meal_id' => 1,
        ])->assertDatabaseHas('favouritefood_meal', [
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
            "foodgroup_id" => factory(Foodgroup::class)->create()->id,
            "alias" => 'my alias',
            "description" => "my favourite food",
            "kcal" => 119,
            "potassium" => 204,
        ];

        $response = $this->post(route('favouritefoods.store', $favouritefood));

        $this->assertDatabaseHas('favouritefoods', $favouritefood);

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

        $response = $this->post(route('favouritefoods.index', $favouritefood));

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

        $response = $this->post(route('favouritefoods.index', $favouritefood));

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

        $response = $this->post(route('favouritefoods.index', $favouritefood));

        $response->assertSessionHasErrors(['potassium']);
    }

    /** @test */
    public function anAuthenticatedUserCanDeleteTheirFavouritefood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $favouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('favouritefoods', ['description' => $favouritefood->description]);

        $this->delete(route('favouritefoods.destroy', ['favouritefood' => $favouritefood]));

        $this->assertDatabaseMissing('favouritefoods', ['description' => $favouritefood->description]);
    }

    /** @test */
    public function anAuthenticatedUserCannotDeleteSomeoneElsesFavouritefood()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $someoneelse = factory(User::class)->create();

        $favouritefood = factory(Favouritefood::class)->create([
            'user_id' => $someoneelse->id,
        ]);

        $this->assertDatabaseHas('favouritefoods', ['description' => $favouritefood->description]);

        $this->delete(route('favouritefoods.destroy', ['favouritefood' => $favouritefood]));

        $this->assertDatabaseHas('favouritefoods', ['description' => $favouritefood->description]);
    }

    /** @test */
    public function aUsersFavouriteFoodsAreSortedByAliasThenDescription()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $fourthFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'alias' => 'b',
            'description' => 'b',
        ]);

        $thirdFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'alias' => 'b',
            'description' => 'a',
        ]);

        $secondFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'alias' => 'a',
            'description' => 'b',
        ]);

        $firstFavouritefood = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'alias' => 'a',
            'description' => 'a',
        ]);

        $response = $this->get(route('favouritefoods.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([
                $firstFavouritefood->description,
                $secondFavouritefood->description,
                $thirdFavouritefood->description,
                $fourthFavouritefood->description,
            ]);
    }

    /** @test */
    public function aUsersFavouritefoodsArePaginated()
    {
        $this->markTestSkipped('revist with custom front end paginator');

        $user = factory(User::class)->create();
        $this->actingAs($user);

      $favouritefoods = factory(Favouritefood::class, (env('PAGINATION_PER_PAGE'))+1)->create([
            'user_id' => $user->id,
            'alias' => 'alias',
            'description' => 'a page1',
        ]);

        $favouritefoods = factory(Favouritefood::class)->create([
            'user_id' => $user->id,
            'alias' => 'alias',
            'description' => 'z page2',
        ]);

        $response = $this->get(route('favouritefoods.index'))
            ->assertSuccessful()
            ->assertSee('a page1')
            ->assertDontSee('z page2');

        $request = $this->call('GET', route('favouritefoods.index'), ["page"=>"2"])
            ->assertSuccessful()
            ->assertSee('z page2');

    }

    /** @test */
    public function a_favouritefood_belongs_to_a_foodgroup() {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroup = factory(Foodgroup::class)->create();

        $favouritefood = factory(Favouritefood::class)->create([
            'foodgroup_id' => $foodgroup->id,
        ]);

        $this->assertEquals($foodgroup->description, $favouritefood->foodgroup->description);
    }

    /** @test */
    public function it_returns_all_Foodgroups_with_all_Favouritefoods() {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $foodgroups = factory(Foodgroup::class,2)->create();
        $favouritefoods = factory(Favouritefood::class, 3)->create();

        $this->get(route('favouritefoods.index'))
            ->assertSuccessful()
            ->assertSee($favouritefoods[0]->description)
            ->assertSee($favouritefoods[1]->description)
            ->assertSee($favouritefoods[2]->description)
            ->assertSee($foodgroups[0]->description)
            ->assertSee($foodgroups[1]->description)
        ;
    }
}
