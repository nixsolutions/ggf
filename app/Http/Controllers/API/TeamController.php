<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Tournament\RemoveTeam;
use App\Team;
use App\TournamentTeam;
use App\Transformers\TeamSearchTransformer;
use App\Transformers\TournamentTeamTransformer;
use Illuminate\Support\Facades\Input;

/**
 * Class TeamController
 * @package App\Http\Controllers\API
 */
class TeamController extends Controller
{
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
     * @param $teamId
     * @return array
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
     *     description="Delete specified team from database",
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
     *     description="Successfully remove specified steam"
     *     )
     * )
     * @param $teamId
     * @return
     */
    public function remove($teamId)
    {
        return TournamentTeam::where(['teamId' => $teamId])->delete();
    }
}