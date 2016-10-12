<?php

namespace App\Listeners\Tournament;

use App\Match;
use App\Tournament as TournamentModel;
use App\Events\Tournament\TournamentWasReset;

/**
 * Class Reset
 * @package App\Listeners\Tournament
 */
class Reset
{
    /**
     * @var TournamentModel
     */
    protected $tournament;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TournamentWasReset $event
     * @return void
     */
    public function handle(TournamentWasReset $event)
    {
        $this->tournament = $event->tournament;

        $this->reset();
    }

    protected function reset()
    {
        $this->cleanupMatches();
    }

    /**
     * @return mixed
     */
    protected function cleanupMatches()
    {
        return Match::where(['tournamentId' => $this->tournament->id])->delete();
    }
}
