<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
use App\Member;
use Illuminate\Database\Eloquent;

/**
 * Class TeamMember
 * @package App
 */
class TeamMember extends Model {

    /**
     * @var string
     */
    protected $table = 'team_members';

    /**
     * @var array
     */
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

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return Eloquent\Relations\BelongsTo
     */
    public function member()
	{
		return $this->belongsTo(Member::class, 'memberId');
	}

    /**
     * @return Eloquent\Relations\BelongsTo
     */
    public function tournamentTeam()
	{
		return $this->belongsTo(TournamentTeam::class, 'tournamentTeamId');
	}

}