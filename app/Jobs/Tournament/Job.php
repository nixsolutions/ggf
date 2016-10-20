<?php

namespace App\Jobs\Tournament;

use App\Tournament;

/**
 * Class Job
 * @package App\Jobs\Tournament
 */
abstract class Job extends \App\Jobs\Job
{
    /**
     * @var Tournament
     */
    protected $tournament;

    /**
     * Job constructor.
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->setTournament($tournament);
    }

    /**
     * @param Tournament $tournament
     * @return mixed
     */
    abstract protected function setTournament(Tournament $tournament);
}