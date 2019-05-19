<?php

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


Route::group(['middleware' => 'tokenview'], function(){
    Route::get('/', 'HomeController@root')->name('root');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/test', 'TestController@index')->name('test');

    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile', 'UserController@update_avatar');

    Route::get('/circles', 'CircleController@configure')->name('circles');

    Route::get('/post/{id}', 'PostController@view')->name('post');
});





Auth::routes();
