<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateLeague;
use App\League;
use App\Transformers\LeagueTransformer;
use App\Transformers\TeamTransformer;
use Illuminate\Support\Facades\Input;

/**
 * Class LeagueController
 * @package App\Http\Controllers\API
 */
class LeagueController extends Controller
{
    /**
     * @return array
     */
    public function catalogue()
    {
        return $this->response->collection(League::all(), new LeagueTransformer($this->response), 'leagues');
    }

    /**
     * Create new league
     *
     * @param CreateLeague $request
     * @return array
     */
    public function store(CreateLeague $request)
    {
        $league = League::create($request->input('league'));

        return $this->response->collection(League::where(['id' => $league->id])->get(), new LeagueTransformer(), 'leagues');
    }

    /**
     * @return array
     */
    public function teams()
    {
        $collection = League::findOrFail(Input::get('leagueId'))->teams();

        return $this->response->collection($collection->get(), new TeamTransformer(), 'leagueTeams');
    }
}