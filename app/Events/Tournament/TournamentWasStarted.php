<?php

namespace App\Events\Tournament;

use Illuminate\Queue\SerializesModels;

/**
 * Class TournamentWasStarted
 * @package App\Events\Tournament
 */
class TournamentWasStarted extends AbstractTournamentDrawEvent
{
    use SerializesModels;
}