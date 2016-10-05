<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'cors'], function() {

    Route::post('/auth/facebook/token', 'Auth\FacebookController@token');
    Route::post('/auth/logout', 'AuthController@logout');

    Route::resource('tournament', 'TournamentController', [
        'only' => ['index', 'store']
    ]);

    Route::resource('team', 'TeamController', [
        'only' => ['index', 'store']
    ]);

    Route::resource('member', 'MemberController', [
        'only' => ['index', 'store']
    ]);

    Route::resource('match', 'MatchController', [
        'only' => ['index', 'store']
    ]);

    Route::get('/', function () {
        return view('app');
    });

    Route::get('/welcome', function () {
        return view('welcome');
    });
});