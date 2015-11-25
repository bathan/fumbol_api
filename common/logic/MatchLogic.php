<?php

namespace fumbol\common\logic;

use fumbol\common\data\entities\Match;
use fumbol\common\data\entities\User;
use fumbol\common\data\entities\Venue;
use fumbol\common\Utilities;

class MatchLogic {


    const STATUS_NO_MATCH = 'NO_MATCH';
    const STATUS_LFM = 'LFM';
    const STATUS_FULL = 'FULL';

    private $match_dates;
    private $match_status = self::STATUS_NO_MATCH;
    private $venue;
    private $players_missing;
    private $current_match;

    const NUMBER_OF_TEAMS = 2;
    const TEAM_NAME_PREFIX = 'Equipo ';

    const MAX_PLAYERS_PER_MATCH = 10; //-- TODO:: This should be part of the venue information.


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

        @$match_day_info = $this->match_dates[$day_of_the_week];

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
                $this->match_status = self::STATUS_LFM;
            }

            //-- Check if the status is the same in BBDD now
            if($this->current_match->getStatus() != $this->match_status) {
                //-- Discrepancy. Update BBDD
                $this->current_match->setStatus($this->match_status);
                $this->current_match->persist();
            }

            //-- Get Players
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
            $today_match->setStatus(MatchLogic::STATUS_LFM);
            $today_match->persist();

        }

        return $today_match;
    }

    public function addUserToMatch($user_id,$match_id) {
        try {
            $match_player_id = Match::addUserToMatch($match_id,$user_id);

            //-- TODO:: Team Choosing
            return $match_player_id;

        }catch(\Exception $e) {
            throw $e;
        }

    }

    public function getAllMatchPlayers($match_id) {
        try {

            $match_players = Match::getAllMatchPlayers($match_id);

            //-- Get Match Player Info
            $list_of_ids = [];
            $return = [];

            foreach($match_players as $mp) {
                $user_id = $mp['user_id'];

                $list_of_ids[] = $user_id;
                $return[$user_id] = $mp;
            }

            $user_info = User::getByListOfIds($list_of_ids);

            foreach($user_info as $user_object) {
                $user_data = $user_object->toArray();
                $user_id = $user_data['user_id'];
                $return[$user_id] = array_merge($return[$user_id] ,$user_data);
            }

            return $return;

        }catch (\Exception $e) {
            throw $e;
        }
    }

    //-- TODO:: LEFT for later -> prechoosing teams

    public function getMatchTeams($match_id) {

        $match_teams_from_db = Match::getMatchTeams($match_id);

        $result = [];

        if(count($match_teams_from_db)>0) {
            //-- We have teams, get the player info
            foreach($match_teams_from_db as $match_team_from_db ) {

                $match_team_id = $match_team_from_db["match_team_id"];
                $team_name = $match_team_from_db["team_name"];
                if($team_name=='') {
                    $team_name = self::TEAM_NAME_PREFIX.$match_team_id;
                }
                $created_date = $match_team_from_db["created_date"];

                //-- Get All
                $result[] = ['team_name'=>$team_name,'match_team_id'=>$match_team_id,'created_date'=>$created_date];
            }
        }

        return $result;
    }

    public function addMatchTeam($match_id,$team_name='') {
        try {

            $match_team_id = Match::addMatchTeam($match_id,$team_name);
            return $match_team_id;

        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function assignMatchTeamIdToPlayers($match_id,$match_team_id,$list_of_user_ids) {
        try {

             Match::assignMatchTeamIdToPlayers($match_id,$match_team_id,$list_of_user_ids);

        }catch (\Exception $e) {
            throw $e;
        }
    }






}