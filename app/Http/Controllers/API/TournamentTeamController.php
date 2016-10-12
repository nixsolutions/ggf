<?php

namespace App\Http\Controllers\API;

use App\Tournament;
use App\TournamentTeam;
use App\Transformers\TournamentTeamTransformer;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\Tournament\AddTeam;
use Symfony\Component\Process\Exception\LogicException;

/**
 * Class TournamentTeamController
 * @package App\Http\Controllers\API
 */
class TournamentTeamController extends Controller
{

    /**
     * @SWG\Get(
     *     path="/api/v1/teams",
     *     description="Returns all teams from specified tournament",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="query",
     *         name="tournamentId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of teams"
     *     )
     * )
     */
    public function catalogue()
    {
        $collection = TournamentTeam::with('Team')->where(['tournamentId' => Input::get('tournamentId')]);

        return $this->response->collection($collection->get(), new TournamentTeamTransformer(), 'teams');
    }

    /**
     * @SWG\Post(
     *     path="/api/v1/teams",
     *     description="Add new team to specified tournament",
     *     operationId="add",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="query",
     *         name="team[tournamentId]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Team id",
     *         in="query",
     *         name="team[teamId]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully add new team"
     *     )
     * )
     */
    public function add(AddTeam $request)
    {
        $tournament = Tournament::findOrFail($request->input('team.tournamentId'));

        if (Tournament::STATUS_DRAFT !== $tournament->status) {
            throw new LogicException('Team can be assigned only to tournament with draft status.');
        }

        $team = TournamentTeam::create($request->input('team'));

        return $this->response->collection(TournamentTeam::where(['id' => $team->id])->get(), new TournamentTeamTransformer(), 'teams');
    }
}
