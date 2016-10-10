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
     * @param $teamId
     * @return array
     */
    public function find($teamId)
    {
        $collection = TournamentTeam::where(['teamId' => $teamId]);

        return $this->response->collection($collection->get(), new TournamentTeamTransformer(), 'teams');
    }

    /**
     * @return array
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
     * @param $teamId
     * @param RemoveTeam $request
     * @return mixed
     */
    public function remove($teamId, RemoveTeam $request)
    {
        return TournamentTeam::where(['teamId' => $teamId])->delete();
    }
}