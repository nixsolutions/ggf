<?php

namespace App\Transformers;

use App\Tournament;
use League\Fractal\TransformerAbstract;

/**
 * Class TournamentTransformer
 * @package App\Transformers
 */
class TournamentTransformer extends TransformerAbstract
{
    /**
     * @param Tournament $tournament
     * @return array
     */
    public function transform(Tournament $tournament)
    {
        $teams = [];

        foreach ($tournament->tournamentTeams as $tournamentTeam) {
            $teams[] = $tournamentTeam->id;
        }

        return [
            'id' => $tournament->id,
            'name' => $tournament->name,
            'owner' => $tournament->owner,
            'status' => $tournament->status,
            'type' => $tournament->type,
            'membersType' => $tournament->membersType,
            'teams' => $teams,
            'description' => $tournament->description,
            'updated_at' => $tournament->updated_at->format('F d, Y')
        ];
    }
}
