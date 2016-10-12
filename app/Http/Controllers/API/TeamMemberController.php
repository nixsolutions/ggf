<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Team\AssignTeamMember;
use App\Http\Requests\Team\RemoveTeamMember;
use App\Member;
use App\TeamMember;
use App\Transformers\TeamMemberSearchTransformer;
use App\Transformers\TeamMemberTransformer;
use Illuminate\Support\Facades\Input;

/**
 * Class TeamMemberController
 * @package App\Http\Controllers\API
 */
class TeamMemberController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/v1/teamMembers",
     *     description="Returns all members of specified team from tournament",
     *     operationId="catalogue",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament-team id",
     *         in="query",
     *         name="tournamentTeamId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get list of team members"
     *     )
     * )
     */
    public function catalogue()
    {
        $collection = TeamMember::with('Member')->where(['tournamentTeamId' => Input::get('tournamentTeamId')]);

        return $this->response->collection($collection->get(), new TeamMemberTransformer(), 'teamMembers');
    }

    /**
     * @SWG\Post(
     *     path="/api/v1/teamMembers",
     *     description="Create new member to specified team",
     *     operationId="assign",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament-team id",
     *         in="query",
     *         name="teamMember[teamId]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Member id",
     *         in="query",
     *         name="teamMember[memberId]",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully add member to team"
     *     )
     * )
     */
    public function assign(AssignTeamMember $request)
    {
        $input = $request->input('teamMember');

        $attributes = [
            'memberId' => array_get($input, 'memberId'),
            'tournamentTeamId' => array_get($input, 'teamId'),
        ];

        TeamMember::create($attributes);

        return $this->response->collection(TeamMember::where($attributes)->get(), new TeamMemberTransformer(), 'teamMembers');
    }

    /**
     * @SWG\Delete(
     *     path="/api/v1/teamMembers/{teamMemberId}",
     *     description="Remove member from specified team",
     *     operationId="remove",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Member id",
     *         in="path",
     *         name="teamMemberId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully remove member from team"
     *     )
     * )
     */
    public function remove($teamMemberId, RemoveTeamMember $request)
    {
        return TeamMember::where(['memberId' => $teamMemberId])->delete();
    }

    /**
     * @SWG\Get(
     *     path="/api/v1/teamMembers/search",
     *     description="Returns members we search from database",
     *     operationId="search",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Tournament id",
     *         in="query",
     *         name="tournamentId",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get members we search"
     *     )
     * )
     */
    public function search()
    {
        $tournamentId = Input::get('tournamentId');
        $collection = Member::with(['teamMembers', 'tournamentTeams'])->get();

        $collection = $collection->filter(function ($member) use ($tournamentId) {
            $team = $member->tournamentTeams->first(function ($tournamentTeam, $key) use ($tournamentId) {
                return $tournamentTeam->tournamentId == $tournamentId;
            });

            return is_null($team);
        });

        return $this->response->collection(
            $collection,
            new TeamMemberSearchTransformer(),
            'members'
        );
    }
}
