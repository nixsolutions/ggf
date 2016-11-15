<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use Illuminate\Support\Facades\Auth;

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
        $member = factory(Member::class)->create();
        Auth::login($member);

        $path = base_path('tests/test-logo/ligue1.png');
        $uploadedFile = new \Illuminate\Http\UploadedFile($path, null, 'png', null, null, true);

        $data = [
            'name' => 'example',
            'logo' => $uploadedFile,
        ];

        $this->post('/api/v1/leagues', ['league' => $data]);
        $this->assertResponseStatus(200)
            ->seeInDatabase('leagues', [
                'name' => 'example'
            ]);
    }

    /**
     * @dataProvider leagueProvider
     */
    public function testBadValidStoreTeam($expected, $value, $field)
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $this->json('POST', '/api/v1/leagues', ['league' => [$field => $value]])
            ->assertResponseStatus(422)
            ->seeJson($expected);
    }

    public function leagueProvider()
    {
        $logoResponse = [
            'league.logo' => [
                'The league.logo must be a file of type: jpeg, bmp, png, jpg.',
                'The league.logo must be an image.'
            ]
        ];
        return [
            [['The league.name field is required.'], '', 'name'],
            [['The league.name must be at least 3 characters.'], 'e', 'name'],
            [['The league.name may not be greater than 255 characters.'], str_random(256), 'name'],
            [$logoResponse, 'badFile', 'logo']
        ];
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
