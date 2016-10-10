<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use App\Tournament;
use App\League;
use App\Team;
use Illuminate\Support\Facades\Auth;

class TournamentTeamApiTest extends TestCase
{
    use DatabaseTransactions;

    protected $structure = ['id', 'name', 'logoPath', 'teamId', 'tournamentId', 'tournament', 'updated_at'];

    protected function createTournament()
    {
        $member = factory(Member::class)->create();

        $tournament = factory(Tournament::class)->create([
            'owner' => $member->id,
        ]);

        $league = factory(League::class)->create();

        factory(Team::class, 4)->create([
            'leagueId' => $league->id
        ])
            ->each(function ($team, $key) use ($tournament) {
                $tournament->tournamentTeams()->create([
                    'teamId' => $team->id,
                    'tournamentId' => $tournament->id,
                ]);
            });

        $tournament->status = Tournament::STATUS_STARTED;
        $tournament->save();

        return $tournament;
    }

    public function testGetTournamentTeam()
    {
        $tournament = $this->createTournament();

        $this->json('GET', '/api/v1/teams', ['tournamentId' => $tournament->id])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'teams' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testAddTournamentTeam()
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

        $data = [
            'team' => [
                'teamId' => $team->id,
                'tournamentId' => $tournament->id
            ]
        ];

        $this->json('POST', '/api/v1/teams', $data);
        $this->assertResponseStatus(200)
            ->seeInDatabase('tournament_teams', [
                'teamId' => $team->id,
                'tournamentId' => $tournament->id
            ]);
    }
}
