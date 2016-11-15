<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use App\League;
use App\Team;
use App\TeamMember;
use App\Tournament;
use App\TournamentTeam;
use Illuminate\Support\Facades\Auth;

class TeamMemberApiTest extends TestCase
{
    use DatabaseTransactions;

    private $structure = ['id', 'name', 'teamId', 'memberId', 'team'];
    private $structureSearch = ['id', 'text', 'name', 'logoPath'];

    private function createTournament()
    {
        $owner = factory(Member::class)->create();
        $tournament = factory(Tournament::class)->create([
            'owner' => $owner->id,
        ]);

        return $tournament;
    }

    private function createTeamMember()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $tournament = $this->createTournament();

        $league = factory(League::class)->create();
        $team = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $tournamentTeam = factory(TournamentTeam::class)->create([
            'teamId' => $team->id,
            'tournamentId' => $tournament->id
        ]);

        $teamMember = factory(TeamMember::class)->create([
            'tournamentTeamId' => $tournamentTeam->id,
            'memberId' => $member->id
        ]);

        return $teamMember;
    }

    public function testGetTeamMember()
    {
        $teamMember = $this->createTeamMember();

        $this->json('GET', '/api/v1/teamMembers', ['tournamentTeamId' => $teamMember->tournamentTeamId])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'teamMembers' => [
                    '*' => $this->structure
                ]
            ]);
    }

    public function testCreateTeamMember()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $tournament = $this->createTournament();

        $league = factory(League::class)->create();
        $team = factory(Team::class)->create([
            'leagueId' => $league->id
        ]);

        $tournamentTeam = factory(TournamentTeam::class)->create([
            'teamId' => $team->id,
            'tournamentId' => $tournament->id
        ]);

        $data = [
            'teamMember' => [
                'teamId' => $tournamentTeam->id,
                'memberId' => $member->id
            ]
        ];

        $this->json('POST', '/api/v1/teamMembers', $data);
        $this->assertResponseStatus(200)
            ->seeInDatabase('team_members', [
                'tournamentTeamId' => $tournamentTeam->id,
                'memberId' => $member->id
            ]);
    }

    /**
     * @dataProvider teamMemberProvider
     */
    public function testBadValidCreateTeamMember($expected, $value, $field)
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $this->json('POST', '/api/v1/teamMembers', ['teamMember' => [$field => $value]]);
        $this->assertResponseStatus(422)
            ->seeJson($expected);
    }

    public function teamMemberProvider()
    {
        return [
            [['The team member.team id field is required.'], '', 'teamId'],
            [['The team member.team id must be an integer.'], 'badId', 'teamId'],
            [['The selected team member.team id is invalid.'], '0', 'teamId'],
            [['The team member.member id field is required.'], '', 'memberId'],
            [['The team member.member id must be an integer.'], 'badId', 'memberId'],
            [['The selected team member.member id is invalid.'], '0', 'memberId']
        ];
    }

    public function testRemoveMember()
    {
        $teamMember = $this->createTeamMember();

        $this->json('DELETE', '/api/v1/teamMembers/' . $teamMember->memberId);
        $this->assertResponseStatus(200)
            ->dontSeeInDatabase('team_members', [
                'tournamentTeamId' => $teamMember->tournamentTeamId,
                'memberId' => $teamMember->memberId
            ]);
    }

    public function testSearchMember()
    {
        $teamMember = $this->createTeamMember();

        $this->json('GET', '/api/v1/teamMembers/search', ['tournamentId' => $teamMember->tournamentTeam->tournamentId])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'members' => [
                    '*' => $this->structureSearch
                ]
            ]);
    }
}
