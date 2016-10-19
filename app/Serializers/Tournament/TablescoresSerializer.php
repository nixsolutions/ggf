<?php

namespace App\Serializers\Tournament;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * Class TablescoresSerializer
 * @package App\Serializers\Tournament
 */
class TablescoresSerializer
{
    /**
     * @name collection
     * @param EloquentCollection $collection
     * @return mixed
     */
    public function collection(EloquentCollection $collection)
    {
        $tournament = $collection->first()->tournament;

        return $tournament->getScore($collection);
    }
}