<?php

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

    private $structure = ['id', 'name', 'logoPath', 'teamId', 'tournamentId', 'tournament', 'updated_at'];

    private function createTournament()
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
            ->assertStatus(200)
            ->assertJsonStructure([
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
            'teamId' => $team->id,
            'tournamentId' => $tournament->id
        ];

        $this->json('POST', '/api/v1/teams', ['team' => $data])
            ->assertStatus(200);
        $this->assertDatabaseHas('tournament_teams', $data);
    }

    /**
     * @dataProvider tournamentTeamProvider
     */
    public function testBadValidAddTournamentTeam($expected, $value, $field)
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $this->json('POST', '/api/v1/teams', ['team' => [$field => $value]])
            ->assertStatus(422)
            ->assertJsonFragment($expected);
    }

    public function tournamentTeamProvider()
    {
        return [
            [['The team.team id field is required.'], '', 'teamId'],
            [['The team.team id must be an integer.'], 'badId', 'teamId'],
            [['The selected team.team id is invalid.'], '0', 'teamId'],
            [['The team.tournament id field is required.'], '', 'tournamentId'],
            [['The team.tournament id must be an integer.'], 'badId', 'tournamentId'],
            [['The selected team.tournament id is invalid.'], '0', 'tournamentId'],
        ];
    }
}
