<?php

namespace App\Events\Tournament;

use App\Events\Event;
use App\Tournament;

/**
 * Class AbstractTournamentDrawEvent
 * @package App\Events\Tournament
 */
abstract class AbstractTournamentDrawEvent extends Event
{
    /**
     * @var Tournament
     */
    public $tournament;

    /**
     * AbstractTournamentDrawEvent constructor.
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }
}
