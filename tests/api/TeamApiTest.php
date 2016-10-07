<?php
//
//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
//use App\Tests\TestCase;
//use App\Member;
//use App\Tournament;
//use App\League;
//use App\Team;
//use App\TournamentTeam;
//
//class TeamApiTest extends TestCase
//{
//    use DatabaseTransactions;
//
//    protected $structure = ['id', 'name', 'logoPath'];
//
//    public function testGetTeam()
//    {
//        $member = factory(Member::class)->create();
//        $tournament = factory(Tournament::class)->create([
//            'owner' => $member->id,
//        ]);
//
//        $league = factory(League::class)->create();
//        $team = factory(Team::class)->create([
//            'leagueId' => $league->id
//        ]);
//
//        $tournamentTeam = factory(TournamentTeam::class)->create([
//            'teamId' => $team->id,
//            'tournamentId' => $tournament->id
//        ]);
//
//        $this->get('/api/v1/teams/' . $team->id)
//            ->assertResponseStatus(200)
//            ->seeJsonStructure([
//                'teams' => [
//                    '*' => $this->structure
//                ]
//            ]);
//    }
//}
