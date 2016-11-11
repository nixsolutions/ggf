<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class Team
 * @package App
 */
class Team extends Model
{

    /**
     * @var string
     */
    protected $table = 'teams';

    /**
     * @var array
     */
    protected $fillable = ['name', 'logoPath', 'leagueId'];

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

    /**
     * @param $request
     * @return static
     */
    public function addTeam($request)
    {
        $mime = $request->leagueTeam['logo']->getMimeType();
        $mime = explode('/', $mime);
        $fileName = $request->leagueTeam['name'] . '.' . $mime[1];

        Storage::disk('public')->putFileAs('teams-logo/', $request->leagueTeam['logo'], $fileName);

        $team = Team::create([
            'leagueId' => $request->leagueTeam['leagueId'],
            'name' => $request->leagueTeam['name'],
            'logoPath' => 'teams-logo/' . $fileName,
        ]);

        return $team;
    }
}
