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
     * @return array
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