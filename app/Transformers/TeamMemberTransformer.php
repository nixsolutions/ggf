<?php

namespace App\Transformers;

use App\TeamMember;
use League\Fractal\TransformerAbstract;

/**
 * Class TeamMemberTransformer
 * @package App\Transformers
 */
class TeamMemberTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    public function transform(TeamMember $teamMember)
    {
        return [
            'id' => $teamMember->id,
            'name' => $teamMember->member->name,
            'teamId' => $teamMember->tournamentTeamId,
            'memberId' => $teamMember->memberId,
            'team' => $teamMember->tournamentTeamId
        ];
    }
}