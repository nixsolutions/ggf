<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateLeague;
use App\League;
use App\Transformers\LeagueTransformer;
use App\Transformers\TeamTransformer;
use Illuminate\Support\Facades\Input;

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="GGF",
 *         description="This is a sample server GGF",
 *         @SWG\Contact(
 *             email=""
 *         )
 *     )
 * )
 */
class LeagueController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/v1/leagues",
     *     description="Returns all leagues from the database",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of leagues"
     *     )
     * )
     */
    public function catalogue()
    {
        return $this->response->collection(League::all(), new LeagueTransformer($this->response), 'leagues');
    }

    /**
     * @SWG\Post(
     *     path="/api/v1/leagues",
     *     description="Add new league to database",
     *     operationId="store",
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *         description="League name",
     *         in="formData",
     *         name="league[name]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Path to league logo",
     *         in="formData",
     *         name="league[logoPath]",
     *         required=false,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully add new league"
     *     )
     * )
     */
    public function store(CreateLeague $request)
    {
        $league = new League();
        $league = $league->addLeague($request);

        return $this->response->collection(League::where(['id' => $league->id])->get(), new LeagueTransformer(), 'leagues');
    }

    /**
     * @SWG\Get(
     *     path="/api/v1/leagueTeams",
     *     description="Returns all teams from the specified league",
     *     operationId="teams",
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *         description="ID of league which teams we need",
     *         in="query",
     *         name="leagueId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of leagues"
     *     )
     * )
     */
    public function teams()
    {
        $collection = League::findOrFail(Input::get('leagueId'))->teams();

        return $this->response->collection($collection->get(), new TeamTransformer(), 'leagueTeams');
    }
}