<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route:: group(['middleware' => 'auth'], function(){
    Route::get('/', 'HomeController@index');

    Route::get('/foods', 'FoodController@index')->name('foods.index');
    Route::get('/foods/{food}', 'FoodController@show')->name('foods.show');

    Route::get('/foodgroups', 'FoodgroupController@index')->name('foodgroups.index');
    Route::get('/foodgroups/{foodgroup}', 'FoodgroupController@show')->name('foodgroups.show');

    Route::get('/favouritefoods/{favouritefood}/meals', 'FavouritefoodMealController@index')->name('favouritefoodsMeals.index');

    Route::get('/favouritefoods', 'FavouritefoodController@index')->name('favouritefoods.index');
    Route::post('/favouritefoods', 'FavouritefoodController@store');

    Route::get('/favouritefoods/{favouritefood}', 'FavouritefoodController@show')->name('favouritefoods.show');
    Route::patch('/favouritefoods/{favouritefood}', 'FavouritefoodController@update');

    Route::get('/meals/{meal}/favouritefoods', 'MealFavouritefoodController@index')->name('mealsFavouritefoods.index');

    Route::get('/meals', 'MealController@index')->name('meals.index');
    Route::get('/meals/{meal}', 'MealController@show')->name('meals.show');

    Route::get('/foodsApi', 'Foods\FoodApiController@index');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
