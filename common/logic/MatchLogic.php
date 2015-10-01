<?php

namespace fumbol\common\logic;

use fumbol\common\data\entities\Match;
use fumbol\common\data\entities\Venue;
use fumbol\common\Utilities;

class MatchLogic {


    const STATUS_NO_MATCH = 1;
    const STATUS_LFM = 2;
    const STATUS_FULL = 3;

    private $match_dates;
    private $match_status = self::STATUS_NO_MATCH;
    private $venue;
    private $players_missing;
    private $current_match;


    /**
     * @return int
     */
    public function getMatchStatus()
    {
        return $this->match_status;
    }

    /**
     * @return Venue|null
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @return mixed
     */
    public function getPlayersMissing()
    {
        return $this->players_missing;
    }

    /**
     * @return Match|null
     */
    public function getCurrentMatch()
    {
        return $this->current_match;
    }

    public function __construct() {

        //-- Get the days we have matches and check if today is a Match Date.
        $this->match_dates = json_decode(_MATCH_DAYS,true);

        $day_of_the_week = date('w');

        $match_day_info = $this->match_dates[$day_of_the_week];

        if(!is_null($match_day_info)) {
            //-- Today is a Match Date
            $venue_id = $match_day_info["venue_id"];

            $this->venue = Venue::getById($venue_id);

            $this->current_match = $this->getOrCreateMatchForToday();

            //-- Check if the number of players for this match is already the max.
            $this->players_missing = $this->venue->getMaxPlayers() - $this->current_match->getPlayerCount();

            if( $this->players_missing == 0) {
                //-- We cannot add more people to this match, so the status is full
                $this->match_status = self::STATUS_FULL;
            }else{
                //-- We still can add some players
                $this->match_dates = self::STATUS_LFM;
            }
        }

    }

    private function getOrCreateMatchForToday() {

        $date_from = Utilities::today_start();
        $date_to = Utilities::today_end();

        $today_match = Match::getMatchByDateRange($date_from,$date_to);

        if(is_null($today_match)) {

            $match_date_time = date("Y-m-d ".$this->venue->getVenueDefaultHour().":00");
            $today_match = new Match();
            $today_match->setVenueId($this->venue->getVenueId());
            $today_match->setPlayerCount(0);
            $today_match->setMatchDateTime($match_date_time);
            $today_match->persist();

        }

        return $today_match;
    }
}