<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use Illuminate\Support\Facades\Auth;
use App\Tournament;
use App\League;
use App\Team;
use App\TournamentTeam;
use App\Match;

class TournamentApiTest extends TestCase
{
    use DatabaseTransactions;

    private $structure = ['id', 'name', 'owner', 'status', 'type', 'teams' => [], 'description'];

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

    private function createMatch($tournament)
    {
        $league = factory(League::class)->create();
        $homeTeam = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $awayTeam = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $homeTournamentTeam = factory(TournamentTeam::class)->create([
            'teamId' => $homeTeam->id,
            'tournamentId' => $tournament->id
        ]);

        $awayTournamentTeam = factory(TournamentTeam::class)->create([
            'teamId' => $awayTeam->id,
            'tournamentId' => $tournament->id
        ]);

        factory(Match::class)->create([
            'tournamentId' => $tournament->id,
            'homeTournamentTeamId' => $homeTournamentTeam->id,
            'awayTournamentTeamId' => $awayTournamentTeam->id
        ]);
    }

    public function testGetTournaments()
    {
        $this->createTournament();

        $this->get('/api/v1/tournaments')
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'tournaments' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testGetTournament()
    {
        $tournament = $this->createTournament();

        $this->get('/api/v1/tournaments/' . $tournament->id)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'tournaments' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testTournamentTablescores()
    {
        $member = factory(Member::class)->create();
        $tournament = factory(Tournament::class)->create([
            'owner' => $member->id,
        ]);

        $this->createMatch($tournament);

        $this->json('GET', '/api/v1/tablescores', ['tournamentId' => $tournament->id])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'tablescore' => [
                    '*' => [
                        'id',
                        'position',
                        'team',
                        'matches',
                        'wins',
                        'draws',
                        'losts',
                        'points',
                        'goalsScored',
                        'goalsAgainsted',
                        'goalsDifference'
                    ]
                ]
            ]);
    }

    public function testTournamentStandings()
    {
        $member = factory(Member::class)->create();
        $tournament = factory(Tournament::class)->create([
            'owner' => $member->id,
        ]);

        $this->createMatch($tournament);

        $this->json('GET', '/api/v1/standings', ['tournamentId' => $tournament->id])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'standings' => [
                    '*' => [
                        'id',
                        'tournament',
                        'round',
                        'homeTeam',
                        'homeTeamName',
                        'awayTeam',
                        'awayTeamName',
                        'matches'
                    ]
                ]
            ]);
    }

    public function testTournamentCreate()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $data = [
            'name' => 'example',
            'description' => 'example',
        ];

        $this->json('POST', '/api/v1/tournaments', ['tournament' => $data]);
        $this->assertResponseStatus(200)
            ->seeInDatabase('tournaments', $data);
    }

    public function testTournamentUpdate()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);
        $tournament = $this->createTournament();

        $data = [
            'name' => 'another example',
            'type' => 'league',
            'status' => 'draft',
            'membersType' => 'single',
            'description' => 'another example'
        ];

        $this->json('PUT', 'api/v1/tournaments/' . $tournament->id, ['tournament' => $data])
            ->assertResponseStatus(200)
            ->seeInDatabase('tournaments', $data);
    }
}
