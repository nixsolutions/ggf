<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;

class LeagueApiTest extends TestCase
{
    use DatabaseTransactions;

    protected $structure = ['id', 'name', 'logoPath'];
    protected $teamStructure = ['id', 'leagueId', 'name', 'logoPath', 'updated_at'];

    public function testGetLeagueCatalog()
    {
        $leagues = factory(\App\League::class, 3)->create();
        $this->get('/api/v1/leagues')
            ->seeJsonStructure([
                'leagues' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testCreateLeague()
    {
        $data = [
            'name' => 'example',
            'logoPath' => ' '
        ];

        $this->json('POST', '/api/v1/leagues', ['league' => $data]);
        $this->assertResponseStatus(200)
            ->seeInDatabase('leagues', $data);
    }

    public function testGetLeagueTeams()
    {
        $league = factory(\App\League::class)->create();
        $teams = factory(\App\Team::class, 4)->create([
            'leagueId' => $league->id
        ]);

        $this->json('GET', '/api/v1/leagueTeams', ['leagueId' => $league->id])
            ->seeJsonStructure([
                'leagueTeams' => [
                    '*' => $this->teamStructure
                ]
            ]);
    }
}
