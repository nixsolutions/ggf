<?php

namespace App\Transformers;

use App\Member;
use App\TeamMember;
use Illuminate\Support\Debug\Dumper;
use League\Fractal\TransformerAbstract;

/**
 * Class TeamMemberSearchTransformer
 * @package App\Transformers
 */
class TeamMemberSearchTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param Member $member
     * @return array
     */
    public function transform(Member $member)
    {
        return [
            'id' => $member->id,
            'text' => $member->name,
            'name' => $member->name,
            'logoPath' => null
        ];
    }
}