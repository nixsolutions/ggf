<?php

namespace App\Events;

use App\Match;
use Illuminate\Queue\SerializesModels;

/**
 * Class MatchWasFinished
 * @package App\Events
 */
class MatchWasFinished extends Event
{
    use SerializesModels;

    /**
     * @var Match
     */
    public $match;

    /**
     * Create a new event instance.
     * MatchWasFinished constructor.
     * @param Match $match
     */
    public function __construct(Match $match)
    {
        $this->match = $match;
    }
}
