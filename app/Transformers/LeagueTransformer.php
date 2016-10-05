<?php

namespace App\Transformers;

use App\League;
use League\Fractal\TransformerAbstract;

class LeagueTransformer extends TransformerAbstract
{
    public function transform(League $league)
    {
        return [
            'id' => $league->id,
            'name' => $league->name,
            'logoPath' => $league->logoPath
        ];
    }
}