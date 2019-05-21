<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/tags/trends/', 'TrendsController@index')->name('trends');

    Route::get('/circles/{id}/users/', 'CircleUsersController@index')->name('indexCircleUsers');
    Route::post('/circles/{id}/users/', 'CircleUsersController@store')->name('storeircleUsers');

    Route::post('/posts/{postId}/best', 'PostController@approbe')->name('approbe');
    Route::get('/feed', 'FeedController@index')->name('feed');
    Route::apiResources([
        'tags' => 'TagController',
        'states' => 'StateController',
        'posts' => 'PostController',
        'answers' => 'AnswerController',
        'users' => 'UserController',
        'circles' => 'CircleController',
        'likes' => 'LikeController'
    ]);
});

