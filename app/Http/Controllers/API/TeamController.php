<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Tournament\RemoveTeam;
use App\League;
use App\Team;
use App\TournamentTeam;
use App\Transformers\TeamSearchTransformer;
use App\Transformers\TeamTransformer;
use App\Transformers\TournamentTeamTransformer;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\CreateTeam;

/**
 * Class TeamController
 * @package App\Http\Controllers\API
 */
class TeamController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/v1/teams/all",
     *     description="Returns all teams from the database",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of teams"
     *     )
     * )
     */
    public function catalogue()
    {
        return $this->response->collection(Team::all(), new TeamTransformer($this->response), 'teams');
    }

    /**
     * @SWG\Get(
     *     path="/api/v1/teams/{teamId}",
     *     description="Returns specified team from tournament",
     *     operationId="find",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Team id",
     *         in="path",
     *         name="teamId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get specified team"
     *     )
     * )
     */
    public function find($teamId)
    {
        $collection = TournamentTeam::where(['teamId' => $teamId]);

        return $this->response->collection($collection->get(), new TournamentTeamTransformer(), 'teams');
    }

    /**
     * @SWG\Get(
     *     path="/api/v1/teams/search",
     *     description="Returns teams we search from database",
     *     operationId="search",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="First letters of team name",
     *         in="query",
     *         name="term",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get teams we search"
     *     )
     * )
     */
    public function search()
    {
        $collection = Team::with('tournamentTeams.tournament')->where('name', 'like', Input::get('term') . '%')->get();

        return $this->response->collection(
            $collection,
            new TeamSearchTransformer(),
            'teams'
        );
    }

    /**
     * @SWG\Delete(
     *     path="/api/v1/teams/{teamId}",
     *     description="Delete specified team from tournament",
     *     operationId="remove",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Team id",
     *         in="path",
     *         name="teamId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully remove specified team"
     *     )
     * )
     */
    public function remove($teamId, RemoveTeam $request)
    {
        return TournamentTeam::where(['teamId' => $teamId])->delete();
    }

    /**
     * @SWG\Post(
     *     path="/api/v1/team/add",
     *     description="Add new team to database",
     *     operationId="store",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="League id",
     *         in="formData",
     *         name="team[leagueId]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Team name",
     *         in="formData",
     *         name="team[name]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Team logo",
     *         in="formData",
     *         name="team[logoPath]",
     *         required=false,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully add new team"
     *     )
     * )
     */
    public function store(CreateTeam $request)
    {
        $team = new Team();
        $team = $team->addTeam($request);

        return $this->response->collection(Team::where(['id' => $team->id])->get(), new TeamTransformer(), 'teams');
    }

    /**
     * @SWG\Delete(
     *     path="/api/v1/team/{teamId}",
     *     description="Delete specified team from database",
     *     operationId="delete",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Team id",
     *         in="path",
     *         name="teamId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully remove specified team"
     *     )
     * )
     */
    public function delete($id)
    {
        TournamentTeam::where(['teamId' => $id])->delete();
        return Team::where(['id' => $id])->delete();
    }
}