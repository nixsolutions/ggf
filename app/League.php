<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class League
 * @package App
 */
class League extends Model
{

    /**
     * @var string
     */
    protected $table = 'leagues';

    /**
     * @var array
     */
    protected $fillable = ['name', 'logoPath', 'leagueId'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class, 'leagueId');
    }

    /**
     * @param $request
     * @return static
     */
    public function addLeague($request)
    {
        $mime = $request->league['logo']->getMimeType();
        $mime = explode('/', $mime);
        $fileName = $request->league['name'] . '.' . $mime[1];

        Storage::disk('public')->putFileAs('leagues-logo/', $request->league['logo'], $fileName);

        $league = League::create([
            'name' => $request->league['name'],
            'logoPath' => 'leagues-logo/' . $fileName,
        ]);

        return $league;
    }

}