<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TournamentTeam
 * @package App
 */
class TournamentTeam extends Model
{

    /**
     * @var string
     */
    protected $table = 'tournament_teams';

    /**
     * @var array
     */
    protected $fillable = ['tournamentId', 'teamId'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournamentId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'teamId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'tournamentTeamId', 'teamId');
    }
}
