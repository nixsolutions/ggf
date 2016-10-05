<?php

namespace App\Tests\Unit\Events;

use App\League;
use App\Match;
use App\Team;
use App\Tournament;
use App\TournamentTeam;

use App\Serializers\Tournament\StandingsSerializer;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laracasts\TestDummy\Factory;
use Illuminate\Support\Debug\Dumper;

class StandingsSerializerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testSerializerWithMatchesList()
    {
        // @todo
    }
}
