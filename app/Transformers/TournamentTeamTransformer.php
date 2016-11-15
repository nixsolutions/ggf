<?php

namespace App\Transformers;

use App\TournamentTeam;
use League\Fractal\TransformerAbstract;

/**
 * Class TournamentTeamTransformer
 * @package App\Transformers
 */
class TournamentTeamTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param TournamentTeam $tournamentTeam
     * @return array
     */
    public function transform(TournamentTeam $tournamentTeam)
    {
        return [
            'id' => $tournamentTeam->id,
            'name' => $tournamentTeam->team->name,
            'logoPath' => $tournamentTeam->team->logoPath,
            'teamId' => $tournamentTeam->team->id,
            'tournamentId' => $tournamentTeam->tournamentId,
            'tournament' => $tournamentTeam->tournamentId,
            'updated_at' => $tournamentTeam->team->updated_at->format('F d, Y')
        ];
    }
}
