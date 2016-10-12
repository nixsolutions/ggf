<?php

namespace App\Transformers;

use App\League;
use League\Fractal\TransformerAbstract;

/**
 * Class LeagueTransformer
 * @package App\Transformers
 */
class LeagueTransformer extends TransformerAbstract
{
    /**
     * @param League $league
     * @return array
     */
    public function transform(League $league)
    {
        return [
            'id' => $league->id,
            'name' => $league->name,
            'logoPath' => $league->logoPath
        ];
    }
}