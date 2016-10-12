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
}
