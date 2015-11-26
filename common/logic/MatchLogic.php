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

    public function checkAndCreateTeams($match_id) {
        try{
            $teams_created = false;

            //-- Build Response. Get current Match and check if we have teams
            $current_teams = $this->getMatchTeams($match_id);

            if(count($current_teams)==0) {
                //-- Check if we need to create teams

                $all_players = $this->getAllMatchPlayers($match_id);
                $max_players_cap = (count($all_players) == MatchLogic::MAX_PLAYERS_PER_MATCH);
                $players_in_teams = Utilities::getPlayersInTeams($all_players);
                $no_teams = count($players_in_teams) == 0;

                if ($max_players_cap && $no_teams) {
                    //-- Assign Teams

                    //-- TODO :: /// Player Selection Plugin Framework

                    /// --- SHUFFLE SELECTOR V 1.0
                    $this->createTeams($match_id,$all_players);
                    $teams_created = true;
                }
            }

            return $teams_created;

        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function shuffle($match_id) {

        $all_players = $this->getAllMatchPlayers($match_id);

        if(count($all_players)==0) {
            throw new \Exception("No hay jugadores para mezclar");
        }

        $max_players_cap = (count($all_players) == MatchLogic::MAX_PLAYERS_PER_MATCH);

        if(!$max_players_cap) {
            throw new \Exception("No podés volver a barajar sin todas las cartas papá");
        }

        $this->deleteTeams($match_id);
        $this->createTeams($match_id,$all_players);
    }

    private function deleteTeams($match_id) {

        Match::deleteTeamsForMatch($match_id);
        Match::clearMatchTeamsInMatch($match_id);
    }

    private function createTeams($match_id,$all_players) {

        //-- TODO:: El plugin de equipos debería llamarse aqui.

        shuffle($all_players);
        $teams = array_chunk($all_players, (MatchLogic::MAX_PLAYERS_PER_MATCH / 2), true);

        //-- We have two teams
        foreach($teams as $t_id=>$players) {
            //--Insert Team
            $match_team_id =$this->addMatchTeam($match_id);
            $list_of_user_ids = [];

            //-- Update users in this match, with its correpondant team id
            foreach($players as $p) {
                $list_of_user_ids[] = $p['user_id'];
            }

            if(count($list_of_user_ids)>0) {
                $this->assignMatchTeamIdToPlayers($match_id,$match_team_id,$list_of_user_ids);
            }
        }


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

            if(count($match_players)==0) {
                throw new \Exception("No match players for match ".$match_id);
            }
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

    public function confirmPlayerInMatch($user_id,$match_id) {
        try {

            Match::confirmUserIdInMatchTeam($user_id,$match_id);

        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function assignMatchCaptain($match_id) {
        try {

            $match_players = Match::getAllMatchPlayers($match_id);

            if(count($match_players)==0) {
                throw new \Exception("No match players for match ".$match_id);
            }

            if(count($match_players) < self::MAX_PLAYERS_PER_MATCH) {
                throw new \Exception("Tienen que estar todos para elegir al capitán");
            }

            $current_cap = Match::getFlaggedPlayers($match_id);
            if(count($current_cap)==0) {
                $random_player = array_rand($match_players);
                $user_id = $random_player['user_id'];

                Match::flagMatchPlayer($match_id,$user_id);
            }else{
                $user_id = $current_cap[0]["user_id"];
            }

            return $user_id;

        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function mariconear($user_id,$match_id) {
        try {

            Match::unConfirmUserIdInMatchTeam($user_id,$match_id);
            Match::deleteUserInMatchTeam($user_id,$match_id);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public function sendMatchFullEmail(Match $match) {

        var_dump($match);
        die();
    }

    public function addUserToTeamWithLessPlayers($user_id,$match_id)
    {

        $players_in_teams = Utilities::getPlayersInTeams($this->getAllMatchPlayers($match_id));

        //-- We should Have two teams now
        $count_of_first_team_players = count(reset($players_in_teams));
        $count_of_second_team_players = count(end($players_in_teams));


        if ($count_of_first_team_players >= $count_of_second_team_players) {
            $add_to_match_team_id = array_keys($players_in_teams)[1];
        } else {
            $add_to_match_team_id = array_keys($players_in_teams)[0];
        }

        Match::assignMatchTeamIdToPlayers($match_id, $add_to_match_team_id, [$user_id]);
    }
}