<?php

namespace App\Listeners\Tournament;

use App\Events\Tournament\AbstractTournamentDrawEvent;
use App\Jobs\Tournament\DrawKnockOut;
use App\Jobs\Tournament\DrawLeague;
use App\Tournament;

/**
 * Class Draw
 * @package App\Listeners\Tournament
 */
class Draw
{
    /**
     * Handle the event.
     *
     * @param  AbstractTournamentDrawEvent $event
     * @return void
     * @throws \RuntimeException
     */
    public function handle(AbstractTournamentDrawEvent $event)
    {
        $tournament = $event->tournament;

        switch ($tournament->type) {
            case Tournament::TYPE_LEAGUE:
                $job = new DrawLeague($tournament);
                $job->handle();
                break;

            case Tournament::TYPE_KNOCK_OUT:
                $job = new DrawKnockOut($tournament);
                $job->handle();
                break;
            case Tournament::TYPE_MULTISTAGE:
                throw new \RuntimeException('Not implemented');
                break;
        }
    }
}
