<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/register', 'Auth\LoginController@register')->name('register');
Route::post('/user', 'UserController@store')->name('user.store');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@index');

    Route::get('/foods', 'FoodController@index')->name('foods.index');


    Route::post('/foods', 'FoodController@store')->name('foods.store');
    Route::get('/foods/new', 'FoodController@create')->name('foods.create');
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

    Route::namespace('Api')->prefix('api')->group(function () {
        Route::namespace('Foods')->prefix('foods/{food}')->group(function () {
            Route::patch('ingredients/{ingredient}', 'IngredientController@update')->name('api.foods.ingredients.update');
        });
    });
//working here
    Route::namespace('Users')->prefix('users/{user}')->group(function () {
        Route::get('/foods', 'FoodController@index')->name('users.foods.index');
        Route::get('/foods/{food}', 'FoodController@show')->name('users.foods.show');
        Route::post('/foods', 'FoodController@store')->name('users.foods.store');
        Route::delete('/foods', 'FoodController@destroy')->name('users.foods.destroy');
    });
});



