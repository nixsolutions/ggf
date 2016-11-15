<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Tournament
 * @package App
 */
class Tournament extends Model
{
    /**
     * @var string
     */
    protected $table = 'tournaments';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'status', 'type', 'membersType'];

    /**
     * @var bool
     */
    public $timestamps = true;

    const MEMBERS_TYPE_SINGLE = 'single';
    const MEMBERS_TYPE_DOUBLE = 'double';

    const TYPE_LEAGUE = 'league';
    const TYPE_KNOCK_OUT = 'knock_out';
    const TYPE_MULTISTAGE = 'multistage';

    const STATUS_DRAFT = 'draft';
    const STATUS_STARTED = 'started';
    const STATUS_COMPLETED = 'completed';

    const MIN_TEAMS_AMOUNT = 2;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentTeams()
    {
        return $this->hasMany(TournamentTeam::class, 'tournamentId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(Match::class, 'tournamentId');
    }

    /**
     * @return array
     */
    public static function getAvailableMembersType()
    {
        return [
            self::MEMBERS_TYPE_SINGLE,
            self::MEMBERS_TYPE_DOUBLE,
        ];
    }

    /**
     * @return array
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_LEAGUE,
            self::TYPE_KNOCK_OUT,
            self::TYPE_MULTISTAGE
        ];
    }

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_STARTED,
            self::STATUS_COMPLETED
        ];
    }

    /**
     * Group matches in tournament into pairs
     *
     * @return Collection
     */
    public function getPairs()
    {
        // get pairs of current round matches
        $pairs = new Collection();

        $this->matches->map(function ($match) use ($pairs) {

            $pairId = [$match->homeTournamentTeam->id, $match->awayTournamentTeam->id];
            sort($pairId);
            $pairId = implode('-', $pairId);

            $pair = $pairs->pull($pairId);

            if (!$pair) {
                $teams = new Collection([
                    $match->homeTournamentTeam->with('Team')->get(),
                    $match->awayTournamentTeam->with('Team')->get()
                ]);

                $pair = new Collection([
                    'id' => $pairId,
                    'round' => $match->round,
                    'teams' => $teams,
                    'matches' => new Collection()
                ]);
            }

            $pair->get('matches')->push($match);

            $pairs->put($pairId, $pair);
        });

        return $pairs;
    }

    private function matchScore($match, $homeTeam, $awayTeam)
    {
        if (Match::STATUS_FINISHED == $match->status) {
            $homeTeam['matches']++;
            $awayTeam['matches']++;
            $homeTeam['goalsScored'] += $match->homeScore;
            $homeTeam['goalsAgainsted'] += $match->awayScore;
            $homeTeam['goalsDifference'] = ($homeTeam['goalsScored'] - $homeTeam['goalsAgainsted']);

            $awayTeam['goalsScored'] += $match->awayScore;
            $awayTeam['goalsAgainsted'] += $match->homeScore;
            $awayTeam['goalsDifference'] = ($awayTeam['goalsScored'] - $awayTeam['goalsAgainsted']);

            switch ($match->resultType) {
                case Match::RESULT_TYPE_HOME_WIN:
                    $homeTeam['wins']++;
                    $homeTeam['points'] += Match::POINTS_WIN;
                    $awayTeam['losts']++;

                    break;
                case Match::RESULT_TYPE_AWAY_WIN:
                    $awayTeam['wins']++;
                    $homeTeam['losts']++;
                    $awayTeam['points'] += Match::POINTS_WIN;

                    break;
                case Match::RESULT_TYPE_DRAW:
                    $homeTeam['draws']++;
                    $awayTeam['draws']++;
                    $homeTeam['points'] += Match::POINTS_DRAW;
                    $awayTeam['points'] += Match::POINTS_DRAW;

                    break;
            }
        }

        return $teams = ['homeTeam' => $homeTeam, 'awayTeam' => $awayTeam];
    }

    /**
     * @todo Make a refactoring for `pair` entity
     * @name getScore
     * @param Collection $matches
     * @return Collection|static
     */
    public function getScore(Collection $matches)
    {
        $score = new Collection();

        $matches->map(function ($match) use ($score) {

            $homeTeam = $score->pull($match->homeTournamentTeam->id);
            $awayTeam = $score->pull($match->awayTournamentTeam->id);

            $defaultTeamData = [
                'matches' => 0,
                'position' => 0,
                'wins' => 0,
                'draws' => 0,
                'losts' => 0,
                'points' => 0,
                'goalsScored' => 0,
                'goalsAgainsted' => 0,
                'goalsDifference' => 0,
            ];

            if (!$homeTeam) {
                $homeTeam = array_merge(
                    [
                        'teamId' => $match->homeTournamentTeam->id,
                        'name' => $match->homeTournamentTeam->team->name,
                    ],
                    $defaultTeamData
                );
            }

            if (!$awayTeam) {
                $awayTeam = array_merge(
                    [
                        'teamId' => $match->awayTournamentTeam->id,
                        'name' => $match->awayTournamentTeam->team->name,
                    ],
                    $defaultTeamData
                );
            }

            $teams =$this->matchScore($match, $homeTeam, $awayTeam);
            $score->put($match->homeTournamentTeam->id, $teams['homeTeam']);
            $score->put($match->awayTournamentTeam->id, $teams['awayTeam']);
        });

        // sort by points and goal difference
        $score = $score->sort(function ($a, $b) {
            if ($b['points'] === $a['points']) {
                return $b['goalsDifference'] - $a['goalsDifference'];
            }

            return $b['points'] - $a['points'];
        });

        $previousRow = null;
        $position = 1;
        $score = $score->map(function ($row) use (&$previousRow, &$position) {
            if ($previousRow
                && $previousRow['points'] > 0
                && $previousRow['points'] == $row['points']
                && $previousRow['goalsDifference'] == $row['goalsDifference']
                && $previousRow['goalsScored'] == $row['goalsScored']
            ) {
                $row['position'] = $previousRow['position'];
            } else {
                $row['position'] = $position;
            }

            $position++;

            $previousRow = $row;

            return $row;
        });

        // alphabetical sort for teams on the same position
        $score = $score->sortBy(function ($team) {
            return $team['position'] . '-' . $team['name'];
        }, SORT_NUMERIC);

        return $score;
    }

    /**
     * @name getWinner
     * @param Collection $matches
     * @return mixed
     */
    public function getWinner(Collection $matches)
    {
        return $this->getScore($matches)->first();
    }

    /**
     * @return int
     */
    public function getCurrentRound()
    {
        $matches = $this->matches()->get();

        if ($matches->count() === 0) {
            return 0;
        } else {
            return $matches->pluck('round')->max();
        }
    }
}
