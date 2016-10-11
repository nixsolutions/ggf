<?php

namespace App\Serializers\Tournament;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class TablescoresSerializer
{
    /**
     * @return Collection
     */
    public function collection(EloquentCollection $collection)
    {
        $tournament = $collection->first()->tournament;

        return $tournament->getScore($collection);
    }
}