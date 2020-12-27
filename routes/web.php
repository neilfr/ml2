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

    Route::post('/foods/{food}/toggle-favourite', 'FoodController@toggleFavourite')->name('foods.toggle-favourite');

    Route::get('/foods/{food}/ingredients', 'FoodIngredientController@index')->name('foods.ingredients.index');
    Route::post('/foods/{food}/ingredients', 'FoodIngredientController@store')->name('foods.ingredients.store');
    Route::patch('/foods/{food}/ingredients/{ingredient}', 'FoodIngredientController@update')->name('foods.ingredients.update');
    Route::delete('/foods/{food}/ingredients/{ingredient}', 'FoodIngredientController@destroy')->name('foods.ingredients.destroy');

    Route::get('/ingredients', 'IngredientController@index')->name('ingredients.index');

    Route::get('/foodgroups', 'FoodgroupController@index')->name('foodgroups.index');
    Route::get('/foodgroups/{foodgroup}', 'FoodgroupController@show')->name('foodgroups.show');
});



