<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App
 */
class Team extends Model {

    /**
     * @var string
     */
    protected $table = 'teams';

    /**
     * @var array
     */
    protected $fillable = ['name','logoPath', 'leagueId'];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamMembers()
	{
		return $this->hasMany(Member::class, 'teamId');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentTeams()
	{
		return $this->hasMany(TournamentTeam::class, 'teamId');
	}

}