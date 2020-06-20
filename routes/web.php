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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');

    Route::get('/foods', 'FoodController@index')->name('foods.index');
    Route::post('/foods', 'FoodController@store')->name('foods.store');
    Route::get('/foods/{food}', 'FoodController@show')->name('foods.show');
    Route::patch('/foods/{food}', 'FoodController@update')->name('foods.update');
    Route::delete('/foods/{food}', 'FoodController@destroy')->name('foods.destroy');

    Route::get('/food/{food}/ingredient', 'FoodIngredientController@index')->name('food.ingredient.index');
    Route::post('/food/{food}/ingredient', 'FoodIngredientController@store')->name('food.ingredient.store');
    Route::patch('/food/{food}/ingredient/{ingredient}', 'FoodIngredientController@update')->name('food.ingredient.update');
    Route::delete('/food/{food}/ingredient/{ingredient}', 'FoodIngredientController@destroy')->name('food.ingredient.destroy');

    Route::get('/foodgroups', 'FoodgroupController@index')->name('foodgroups.index');
    Route::get('/foodgroups/{foodgroup}', 'FoodgroupController@show')->name('foodgroups.show');

    Route::get('/favouritefoods/{favouritefood}/meals', 'FavouritefoodMealController@index')->name('favouritefoodsMeals.index');

    Route::get('/favouritefoods', 'FavouritefoodController@index')->name('favouritefoods.index');
    Route::post('/favouritefoods', 'FavouritefoodController@store')->name('favouritefoods.store');

    Route::get('/favouritefoods/{favouritefood}', 'FavouritefoodController@show')->name('favouritefoods.show');
    Route::patch('/favouritefoods/{favouritefood}', 'FavouritefoodController@update');

    Route::delete('/favouritefoods/{favouritefood}', 'FavouritefoodController@delete')->name('favouritefoods.destroy');

    Route::get('/meals/{meal}/favouritefoods', 'MealFavouritefoodController@index')->name('mealsFavouritefoods.index');

    Route::get('/meals', 'MealController@index')->name('meals.index');
    Route::get('/meals/{meal}', 'MealController@show')->name('meals.show');
    Route::post('/meals', 'MealController@store')->name('meals.store');

    Route::get('/foodsApi', 'Foods\FoodApiController@index');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
