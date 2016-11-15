<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;

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

Route::group(['prefix' => 'v1'], function() {

    Route::get('/leagues', 'API\LeagueController@catalogue');
    Route::get('/leagueTeams', 'API\LeagueController@teams');

    Route::get('/tournaments', 'API\TournamentController@catalogue');
    Route::get('/tournaments/{tournamentId}', 'API\TournamentController@find');

    Route::get('/teams', 'API\TournamentTeamController@catalogue');
    Route::get('/teams/search', 'API\TeamController@search');

    Route::get('/teams/all', 'API\TeamController@catalogue');
    Route::get('/teams/{teamId}', 'API\TeamController@find');

    Route::get('/teamMembers', 'API\TeamMemberController@catalogue');
    Route::get('/teamMembers/search', 'API\TeamMemberController@search');

    Route::get('/tablescores', 'API\TournamentController@tablescores');
    Route::get('/standings', 'API\TournamentController@standings');

    Route::get('/matches', 'API\MatchController@catalogue');

    Route::group(['middleware' => ['before-match-update']], function() {
        Route::put('/matches/{matchId}', 'API\MatchController@update');
    });

    Route::group(['middleware' => ['fb-auth']], function() {
        Route::post('/leagues', 'API\LeagueController@store');

        Route::post('/tournaments', 'API\TournamentController@store');
        Route::put('/tournaments/{tournamentId}', 'API\TournamentController@update');

        Route::post('/teams', 'API\TournamentTeamController@add');
        Route::delete('/teams/{teamId}', 'API\TeamController@remove');

        Route::post('/leagueTeams', 'API\TeamController@store');
        Route::delete('/leagueTeams/{teamId}', 'API\TeamController@delete');

        Route::post('/teamMembers', 'API\TeamMemberController@assign');
        Route::delete('/teamMembers/{teamMemberId}', 'API\TeamMemberController@remove');
    });

    Route::get('/me', 'API\MemberController@current');
});
