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
    Route::post('/leagues', 'API\LeagueController@store')->middleware(Authenticate::class);
    Route::get('/leagueTeams', 'API\LeagueController@teams');

    Route::get('/tournaments', 'API\TournamentController@catalogue');
    Route::post('/tournaments', 'API\TournamentController@store')->middleware(Authenticate::class);
    Route::get('/tournaments/{tournamentId}', 'API\TournamentController@find');
    Route::put('/tournaments/{tournamentId}', 'API\TournamentController@update')->middleware(Authenticate::class);

    Route::get('/teams', 'API\TournamentTeamController@catalogue');
    Route::post('/teams', 'API\TournamentTeamController@add')->middleware(Authenticate::class);
    Route::get('/teams/search', 'API\TeamController@search');

    Route::get('/teams/all', 'API\TeamController@catalogue');
    Route::post('/leagueTeams', 'API\TeamController@store')->middleware(Authenticate::class);
    Route::delete('/leagueTeams/{teamId}', 'API\TeamController@delete')->middleware(Authenticate::class);
    Route::get('/teams/{teamId}', 'API\TeamController@find');
    Route::delete('/teams/{teamId}', 'API\TeamController@remove')->middleware(Authenticate::class);

    Route::get('/teamMembers', 'API\TeamMemberController@catalogue');
    Route::post('/teamMembers', 'API\TeamMemberController@assign')->middleware(Authenticate::class);
    Route::delete('/teamMembers/{teamMemberId}', 'API\TeamMemberController@remove')->middleware(Authenticate::class);
    Route::get('/teamMembers/search', 'API\TeamMemberController@search');

    Route::get('/tablescores', 'API\TournamentController@tablescores');
    Route::get('/standings', 'API\TournamentController@standings');

    Route::get('/matches', 'API\MatchController@catalogue');

    Route::group(['middleware' => ['before-match-update']], function() {
        Route::put('/matches/{matchId}', 'API\MatchController@update');
    });


    Route::get('/me', 'API\MemberController@current');
});
