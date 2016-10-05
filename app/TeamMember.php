<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Member;
use Illuminate\Database\Eloquent;

class TeamMember extends Model {

	protected $table = 'team_members';

	protected $fillable = ['memberId', 'tournamentTeamId'];

	/**
	 * !!! Looks like an ugly workaround !!!
	 *
	 * @return string
	 */
	public function getKeyName()
	{
		return 'tournamentTeamId';
	}

	public $timestamps = false;

	public function member()
	{
		return $this->belongsTo(Member::class, 'memberId');
	}

	public function tournamentTeam()
	{
		return $this->belongsTo(TournamentTeam::class, 'tournamentTeamId');
	}

}