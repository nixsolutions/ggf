<?php

namespace App\Transformers;

use App\Match;
use Illuminate\Support\Collection;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\TransformerAbstract;

/**
 * Class StandingsTransformer
 * @package App\Transformers
 */
class StandingsTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param $pair
     * @return array
     */
    public function transform($pair)
    {
        return [
            'id' => $pair['id'],
            'tournament' => $pair['tournamentId'],
            'round' => $pair['round'],
            'homeTeam' => $pair['homeTeamId'],
            'homeTeamName' => $pair['homeTeamName'],
            'awayTeam' => $pair['awayTeamId'],
            'awayTeamName' => $pair['awayTeamName'],
            'matches' => $pair['matches']
        ];
    }
}