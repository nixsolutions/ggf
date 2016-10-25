<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use App\Tournament;
use App\League;
use App\Team;
use App\TournamentTeam;
use Illuminate\Support\Facades\Auth;

class TeamApiTest extends TestCase
{
    use DatabaseTransactions;

    private $structure = ['id', 'name', 'logoPath', 'teamId', 'tournamentId', 'tournament', 'updated_at'];
    private $structureSearch = ['id', 'text', 'logoPath', 'updated_at'];
    private $teamStructure = ['id', 'leagueId', 'name', 'logoPath', 'updated_at'];

    private function createTournamentTeam()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $tournament = factory(Tournament::class)->create([
            'owner' => $member->id,
        ]);

        $league = factory(League::class)->create();
        $team = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $tournamentTeam = factory(TournamentTeam::class)->create([
            'teamId' => $team->id,
            'tournamentId' => $tournament->id
        ]);

        return $tournamentTeam;
    }

    private function createTeam()
    {
        $league = factory(\App\League::class)->create();
        $team = factory(\App\Team::class)->create([
            'leagueId' => $league->id,
        ]);

        return $team;
    }

    public function testGetTeam()
    {
        $tournamentTeam = $this->createTournamentTeam();

        $this->json('GET', '/api/v1/teams/' . $tournamentTeam->teamId)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'teams' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testSearchTeam()
    {
        $league = factory(League::class)->create();
        $team = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $this->json('GET', '/api/v1/teams/search', ['term' => $team->name])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'teams' => [
                    '*' => $this->structureSearch
                ]
            ]);
    }

    public function testTeamRemove()
    {
        $tournamentTeam = $this->createTournamentTeam();

        $this->json('DELETE', '/api/v1/teams/' . $tournamentTeam->teamId);
        $this->assertResponseStatus(200)
            ->dontSeeInDatabase('tournament_teams', [
                'teamId' => $tournamentTeam->teamId,
                'tournamentId' => $tournamentTeam->tournamentId
            ]);
    }

    public function testGetTeamCatalogue()
    {
        $this->createTeam();
        $this->get('/api/v1/teams/all')
            ->seeJsonStructure([
                'teams' => [
                    '*' => $this->teamStructure
                ]
            ]);
    }

    public function testStoreTeam()
    {
        $league = factory(\App\League::class)->create();
        $path = public_path('test-logo/argentina.png');
        $uploadedFile = new \Illuminate\Http\UploadedFile($path, null, 'png', null, null, true);

        $data = [
            'leagueId' => $league->id,
            'name' => 'example',
            'logo' => $uploadedFile,
        ];

        $this->post('/api/v1/team/add', ['team' => $data])
            ->assertResponseStatus(200)
            ->seeInDatabase('teams', [
                'leagueId' => $league->id,
                'name' => 'example'
            ]);
    }

    /**
     * @dataProvider teamProvider
     */
    public function testBadValidStoreTeam($expected, $value, $field)
    {
        $this->json('POST', '/api/v1/team/add', ['team' => [$field => $value]])
            ->assertResponseStatus(422)
            ->seeJson($expected);
    }

    public function teamProvider()
    {
        $logoResponse = [
            'team.logo' => [
                'The team.logo must be a file of type: jpeg, bmp, png, jpg.',
                'The team.logo must be an image.'
            ]
        ];
        return [
            [['The team.league id field is required.'], '', 'leagueId'],
            [['The team.league id must be an integer.'], 'badId', 'leagueId'],
            [['The selected team.league id is invalid.'], '0', 'leagueId'],
            [['The team.name field is required.'], '', 'name'],
            [['The team.name must be at least 3 characters.'], 'e', 'name'],
            [['The team.name may not be greater than 255 characters.'], str_random(256), 'name'],
            [$logoResponse, 'badFile', 'logo']
        ];
    }

    public function testTeamDelete()
    {
        $team = $this->createTeam();
        $this->json('DELETE', '/api/v1/team/' . $team->id);
        $this->assertResponseStatus(200)
            ->dontSeeInDatabase('teams', [
                'id' => $team->id,
                'name' => $team->name
            ]);
    }
}
