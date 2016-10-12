<?php

namespace App\Events\Tournament;

use Illuminate\Queue\SerializesModels;

/**
 * Class RoundHasBeenFinished
 * @package App\Events\Tournament
 */
class RoundHasBeenFinished extends AbstractTournamentDrawEvent
{
    use SerializesModels;
}
