<?php

namespace App\Tests\Unit\Events;

use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StandingsSerializerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testSerializerWithMatchesList()
    {
        // @todo
    }
}
