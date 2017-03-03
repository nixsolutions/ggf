<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MatchUpdate;
use App\Match;
use App\Transformers\MatchTransformer;
use Illuminate\Support\Facades\Input;

/**
 * Class MatchController
 * @package App\Http\Controllers\API
 */
class MatchController extends Controller
{
    /**
     * @SWG\Get(
     *     tags={"Match"},
     *     path="/api/v1/matches",
     *     description="Returns all matches with specified tournamentId from the database",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament-team id",
     *         in="query",
     *         name="teamId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Status of match: started, not_started, finished",
     *         in="query",
     *         name="status",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="query",
     *         name="tournamentId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of matches"
     *     )
     * )
     */
    public function catalogue()
    {
        $teamId = Input::get('teamId');
        $status = Input::get('status');

        $collection = Match::with(['homeTournamentTeam.team', 'awayTournamentTeam.team'])
            ->where('tournamentId', Input::get('tournamentId'))
            ->orderBy('round')->orderBy('id');

        if ($status) {
            $collection->where('status', $status);
        }

        if ($teamId) {
            $collection->where(function ($query) use ($teamId) {
                $query->where('homeTournamentTeamId', $teamId)
                    ->orWhere('awayTournamentTeamId', $teamId);
            });
        }

        return $this->response->collection($collection->get(), new MatchTransformer(), 'matches');
    }

    /**
     * @SWG\Put(
     *     tags={"Match"},
     *     path="/api/v1/matches/{matchId}",
     *     description="Update specified match",
     *     operationId="update",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Match id",
     *         in="path",
     *         name="matchId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="New home score",
     *         in="query",
     *         name="match[homeScore]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="New away score",
     *         in="query",
     *         name="match[awayScore]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="New status",
     *         in="query",
     *         name="match[status]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully update specified match"
     *     )
     * )
     * @param $matchId
     * @param MatchUpdate $request
     * @return array
     */
    public function update($matchId, MatchUpdate $request)
    {
        /**
         * @var $match Match
         */
        $match = Match::findOrFail($matchId);
        $match->update(
            array_get($request->only(['match.homeScore', 'match.awayScore', 'match.status']), 'match')
        );

        return $this->response->collection(Match::where(['id' => $matchId])->get(), new MatchTransformer(), 'matches');
    }
}
