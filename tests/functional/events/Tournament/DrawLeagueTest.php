<?php

namespace App\Tests\Unit\Events\Tournament;

use App\League;
use App\Match;
use App\Team;
use App\Tournament;
use App\TournamentTeam;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Member;

class DrawLeagueTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @param $teamsAmount
     * @param $matcheAmount
     *
     * @dataProvider tournamentTeamsProvider
     */
    public function testSuccessLeagueDrawWithDifferrentTeamsAmount($teamsAmount, $matchesAmount)
    {
        $member = factory(Member::class)->create();
        $tournament = factory(Tournament::class)->create([
            'owner' => $member->id,
            'type' => Tournament::TYPE_LEAGUE
        ]);

        /**
         * @var $tournament Tournament
         */
        $league = factory(League::class)->create();

        factory(Team::class, $teamsAmount)->create([
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

        $this->assertTrue($tournament instanceof Tournament);
        // verify total matches amount
        $this->assertEquals($matchesAmount, $tournament->matches()->getResults()->count());

        /**
         * @var $matches Collection
         * @var $team TournamentTeam
         */
        $matches = Match::where(['tournamentId' => $tournament->id])->get();

        foreach ($tournament->tournamentTeams()->getResults() as $team) {
            // verify matches per team
            $this->assertEquals(
                ($teamsAmount - 1) * 2,
                $matches->filter(function ($match) use ($team) {
                    return ($match->homeTournamentTeamId == $team->id
                        || $match->awayTournamentTeamId == $team->id);
                })->count()
            );
        }
    }

    public function tournamentTeamsProvider()
    {
        return [
            [
                'teamsAmount' => 2,
                'matchesCount' => 2,
            ],
            [
                'teamsAmount' => 3,
                'matchesCount' => 6,
            ],
            [
                'teamsAmount' => 4,
                'matchesCount' => 12,
            ],
            [
                'teamsAmount' => 5,
                'matchesCount' => 20,
            ],
            [
                'teamsAmount' => 6,
                'matchesCount' => 30,
            ],
            [
                'teamsAmount' => 7,
                'matchesCount' => 42,
            ],
            [
                'teamsAmount' => 10,
                'matchesCount' => 90,
            ],
            [
                'teamsAmount' => 11,
                'matchesCount' => 110,
            ],
            [
                'teamsAmount' => 12,
                'matchesCount' => 132,
            ],
            [
                'teamsAmount' => 13,
                'matchesCount' => 156,
            ],
            [
                'teamsAmount' => 14,
                'matchesCount' => 182,
            ],
            [
                'teamsAmount' => 15,
                'matchesCount' => 210,
            ],
            [
                'teamsAmount' => 16,
                'matchesCount' => 240,
            ],
            [
                'teamsAmount' => 17,
                'matchesCount' => 272,
            ],
            [
                'teamsAmount' => 18,
                'matchesCount' => 306,
            ],
            [
                'teamsAmount' => 19,
                'matchesCount' => 342,
            ],
            [
                'teamsAmount' => 20,
                'matchesCount' => 380,
            ],
        ];
    }
}
