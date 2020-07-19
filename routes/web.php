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

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/sort', 'HomeController@index');
    Route::post('/sort', 'AjaxController@sort');
    Route::post('/basket/{product_id}', 'AjaxController@basket');

    Route::resource('api-basket', 'BasketController');


    Route::group(['middleware'	=>	'auth'], function(){
        Route::get('/profile', 'ProfileController@index')->name('profile.show');
        Route::post('/profile', 'ProfileController@store')->name('profile.store');
        Route::get('/logout', 'AuthController@logout')->name('logout');
        Route::get('/order', 'OrderController@index')->name('order');
        Route::get('/order-store', 'OrderController@store')->name('order.store');
        Route::post('/order-basket/{product_id}', 'AjaxController@orderBasket');
    });

    Route::group(['middleware'	=>	'guest'], function(){
        Route::get('/register', 'AuthController@registerForm')->name('register.form');
        Route::post('/register', 'AuthController@register')->name('register');
        Route::get('/login','AuthController@loginForm')->name('login.form');
        Route::post('/login', 'AuthController@login')->name('login');
    });
