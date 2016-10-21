<?php

namespace App\Transformers;

use App\Match;
use League\Fractal\TransformerAbstract;

/**
 * Class MatchTransformer
 * @package App\Transformers
 */
class MatchTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param Match $match
     * @return array
     */
    public function transform(Match $match)
    {
        return [
            'id' => $match->id,
            'round' => $match->round,
            'homeTeam' => $match->homeTournamentTeamId,
            'homeTeamId' => $match->homeTournamentTeamId,
            'awayTeam' => $match->awayTournamentTeamId,
            'awayTeamId' => $match->awayTournamentTeamId,
            'homeScore' => $match->homeScore,
            'homePenaltyScore' => $match->homePenaltyScore,
            'awayScore' => $match->awayScore,
            'awayPenaltyScore' => $match->awayPenaltyScore,
            'tournamentId' => $match->tournamentId,
            'status' => $match->status,
            'gameType' => $match->gameType,
            'resultType' => $match->resultType
        ];
    }
}
