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
// Auth
// Route::get('/login', 'LoginController@create')->name('login');
// Route::post('/login', 'LoginController@store')->name('login.store');
// Route::get('/logout', 'LoginController@destroy')->name('logout');
// Route::post('login', 'LoginController@login')->name('login.attempt');
// ->middleware('guest');

// Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm');
// ->middleware('guest');
Route::post('/login')->name('login.attempt')->uses('Auth\LoginController@loginAttempt');
    // Route::post('/login', 'LoginController@login')->middleware('guest')->name('login.attempt');
    // Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');
Route::get('/register')->name('register')->uses('Auth\LoginController@register');
Route::post('/user')->name('user.store')->uses('UserController@store');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

    Route::get('/home', 'HomeController@index')->name('home');
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



