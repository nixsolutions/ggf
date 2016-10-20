<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;

class LeagueApiTest extends TestCase
{
    use DatabaseTransactions;

    private $structure = ['id', 'name', 'logoPath'];
    private $teamStructure = ['id', 'leagueId', 'name', 'logoPath', 'updated_at'];

    public function testGetLeagueCatalog()
    {
        factory(\App\League::class, 3)->create();
        $this->get('/api/v1/leagues')
            ->seeJsonStructure([
                'leagues' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testCreateLeague()
    {
        $path = public_path('leagues-logo/ligue1.png');
        $uploadedFile = new \Illuminate\Http\UploadedFile($path, null, 'png', null, null, true);

        $data = [
            'name' => 'example',
            'logoPath' => $uploadedFile,
        ];

        $this->post('/api/v1/leagues', ['league' => $data]);
        $this->assertResponseStatus(200)
            ->seeInDatabase('leagues', [
                'name' => 'example'
            ]);
    }

    public function testGetLeagueTeams()
    {
        $league = factory(\App\League::class)->create();
        factory(\App\Team::class, 4)->create([
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
