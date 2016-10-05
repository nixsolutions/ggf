<?php

namespace App\Events\Tournament;

use App\Events\Tournament\AbstractTournamentDrawEvent;
use App\Tournament;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoundHasBeenFinished extends AbstractTournamentDrawEvent
{
    use SerializesModels;
}
