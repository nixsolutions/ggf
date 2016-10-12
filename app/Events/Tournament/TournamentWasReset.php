<?php

namespace App\Events\Tournament;

use Illuminate\Queue\SerializesModels;

/**
 * Class TournamentWasReset
 * @package App\Events\Tournament
 */
class TournamentWasReset extends AbstractTournamentDrawEvent
{
    use SerializesModels;
}