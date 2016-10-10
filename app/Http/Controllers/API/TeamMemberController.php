<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Team\AssignTeamMember;
use App\Http\Requests\Team\RemoveTeamMember;
use App\Member;
use App\TeamMember;
use App\Transformers\TeamMemberSearchTransformer;
use App\Transformers\TeamMemberTransformer;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

/**
 * Class TeamMemberController
 * @package App\Http\Controllers\API
 */
class TeamMemberController extends Controller
{
    /**
     * @return array
     */
    public function catalogue()
    {
        $collection = TeamMember::with('Member')->where(['tournamentTeamId' => Input::get('tournamentTeamId')]);

        return $this->response->collection($collection->get(), new TeamMemberTransformer(), 'teamMembers');
    }

    /**
     * @param AssignTeamMember $request
     * @return array
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
     * @param $teamMemberId
     * @param RemoveTeamMember $request
     * @return mixed
     */
    public function remove($teamMemberId, RemoveTeamMember $request)
    {
        return TeamMember::where(['memberId' => $teamMemberId])->delete();
    }

    /**
     * @return array
     */
    public function search()
    {
        $tournamentId = Input::get('tournamentId');
        $collection = Member::with(['teamMembers', 'tournamentTeams'])->get();

        $collection = $collection->filter(function($member) use ($tournamentId) {
            $team = $member->tournamentTeams->first(function($tournamentTeam, $key) use ($tournamentId) {
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
