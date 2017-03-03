<?php

namespace App\Http\Controllers\API;

use App\Match;
use App\Tournament;
use App\Serializers\Tournament\StandingsSerializer;
use App\Serializers\Tournament\TablescoresSerializer;
use App\Transformers\StandingsTransformer;
use App\Transformers\TournamentTransformer;
use App\Transformers\TablescoresTransformer;
use App\Http\Requests\Tournament\Create as CreateTournament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Sorskod\Larasponse\Larasponse;

/**
 * Class TournamentController
 * @package App\Http\Controllers\API
 */
class TournamentController extends Controller
{
    /**
     * TournamentController constructor.
     * @param Larasponse $response
     */
    public function __construct(Larasponse $response)
    {
        $this->response = $response;
        parent::__construct($response);
    }

    /**
     * @SWG\Get(
     *     tags={"Tournament"},
     *     path="/api/v1/tournaments",
     *     description="Returns all tournaments from database",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of tournaments"
     *     )
     * )
     */
    public function catalogue()
    {
        $collection = Tournament::with('tournamentTeams.team')->get();

        return $this->response->collection($collection, new TournamentTransformer($this->response), 'tournaments');
    }

    /**
     * @SWG\Get(
     *     tags={"Tournament"},
     *     path="/api/v1/tournaments/{tournamentId}",
     *     description="Returns specified tournament from database",
     *     operationId="find",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="path",
     *         name="tournamentId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get specified tournament"
     *     )
     * )
     * @param $tournamentId
     * @return array
     */
    public function find($tournamentId)
    {
        $collection = Tournament::with('tournamentTeams.team')->where(['id' => $tournamentId])->get();

        return $this->response->collection($collection, new TournamentTransformer($this->response), 'tournaments');
    }

    /**
     * @SWG\Get(
     *     tags={"Tournament"},
     *     path="/api/v1/tablescores",
     *     description="Returns tablescores",
     *     operationId="tablescores",
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
     *     description="Successfully get tablescores"
     *     )
     * )
     */
    public function tablescores()
    {
        $serializer = new TablescoresSerializer();

        $collection = Match::with(['homeTournamentTeam.team', 'awayTournamentTeam.team'])
            ->where(['tournamentId' => Input::get('tournamentId')]);

        $matches = $collection->get();

        if (!empty($matches->all())) {
            return $this->response->collection(
                $serializer->collection($matches),
                new TablescoresTransformer(),
                'tablescore'
            );
        }

        return $this->response->collection([]);
    }

    /**
     * @SWG\Get(
     *     tags={"Tournament"},
     *     path="/api/v1/standings",
     *     description="Returns standings",
     *     operationId="standings",
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
     *     description="Successfully get standings"
     *     )
     * )
     */
    public function standings()
    {
        $serializer = new StandingsSerializer();

        $collection = Match::with(['homeTournamentTeam.team', 'awayTournamentTeam.team'])
            ->where(['tournamentId' => Input::get('tournamentId')]);

        return $this->response->collection(
            $serializer->collection($collection->get()),
            new StandingsTransformer(),
            'standings'
        );
    }

    /**
     * @SWG\Post(
     *     tags={"Tournament"},
     *     path="/api/v1/tournaments",
     *     description="Create new tournament",
     *     operationId="store",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament name",
     *         in="query",
     *         name="tournament[name]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tournament description",
     *         in="query",
     *         name="tournament[description]",
     *         required=true,
     *         type="string"
     *     ),
     *      @SWG\Parameter(
     *         description="Type of tournament: league, knock_out, multistage",
     *         in="query",
     *         name="tournament[type]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Members type: single, double",
     *         in="query",
     *         name="tournament[membersType]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully add tournament"
     *     )
     * )
     * @param CreateTournament $request
     * @return array
     */
    public function store(CreateTournament $request)
    {
        $input = $request->input('tournament');
        $input['status'] = Tournament::STATUS_DRAFT;

        $tournament = Auth::user()->tournaments()->create($input);
        $tournament = Tournament::where(['id' => $tournament->id])->get();

        return $this->response->collection($tournament, new TournamentTransformer($this->response), 'tournaments');
    }

    /**
     * @SWG\Put(
     *     tags={"Tournament"},
     *     path="/api/v1/tournaments/{tournamentId}",
     *     description="Update specified tournament",
     *     operationId="update",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="path",
     *         name="tournamentId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Tournament name",
     *         in="query",
     *         name="tournament[name]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tournament description",
     *         in="query",
     *         name="tournament[description]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tournament status: draft, started, completed",
     *         in="query",
     *         name="tournament[status]",
     *         required=true,
     *         type="string"
     *     ),
     *      @SWG\Parameter(
     *         description="Tournament type: league, knock_out, multistage",
     *         in="query",
     *         name="tournament[type]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Members type: single, double",
     *         in="query",
     *         name="tournament[membersType]",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully update tournament"
     *     )
     * )
     * @param $tournamentId
     * @return array
     */
    public function update($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $tournament->update([
            'name' => Input::get('tournament.name'),
            'type' => Input::get('tournament.type'),
            'status' => Input::get('tournament.status'),
            'membersType' => Input::get('tournament.membersType'),
            'description' => Input::get('tournament.description')
        ]);
        $tournament = Tournament::where(['id' => $tournamentId])->get();

        return $this->response->collection($tournament, new TournamentTransformer(), 'tournaments');
    }
}
