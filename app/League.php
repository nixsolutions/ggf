<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class League
 * @package App
 */
class League extends Model {

    /**
     * @var string
     */
    protected $table = 'leagues';

    /**
     * @var array
     */
    protected $fillable = ['name','logoPath', 'leagueId'];

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

}