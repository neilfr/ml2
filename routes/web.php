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

Route::get('/home', 'HomeController@index')->name('home');
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

    Route::get('/foodsApi', 'Foods\FoodApiController@index');
});


Auth::routes();

