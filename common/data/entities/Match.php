<?php

namespace fumbol\common\data\entities;

use fumbol\common\data\DBConnectionFactory;

class Match {

    private $match_id;
    private $match_date_time;
    private $venue_id;
    private $player_count = 0;
    private $winning_team_id;
    private $tied;
    private $created_date;
    private $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMatchId()
    {
        return $this->match_id;
    }

    /**
     * @param mixed $match_id
     */
    public function setMatchId($match_id)
    {
        $this->match_id = $match_id;
    }

    /**
     * @return mixed
     */
    public function getMatchDateTime()
    {
        return $this->match_date_time;
    }

    /**
     * @param mixed $match_date_time
     */
    public function setMatchDateTime($match_date_time)
    {
        $this->match_date_time = $match_date_time;
    }

    /**
     * @return mixed
     */
    public function getVenueId()
    {
        return $this->venue_id;
    }

    /**
     * @param mixed $venue_id
     */
    public function setVenueId($venue_id)
    {
        $this->venue_id = $venue_id;
    }

    /**
     * @return mixed
     */
    public function getPlayerCount()
    {
        return $this->player_count;
    }

    /**
     * @param mixed $player_count
     */
    public function setPlayerCount($player_count)
    {
        $this->player_count = $player_count;
    }

    /**
     * @return mixed
     */
    public function getWinningTeamId()
    {
        return $this->winning_team_id;
    }

    /**
     * @param mixed $winning_team_id
     */
    public function setWinningTeamId($winning_team_id)
    {
        $this->winning_team_id = $winning_team_id;
    }

    /**
     * @return mixed
     */
    public function getTied()
    {
        return $this->tied;
    }

    /**
     * @param mixed $tied
     */
    public function setTied($tied)
    {
        $this->tied = $tied;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * @param mixed $created_date
     */
    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
    }




    public function persist() {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();


            if(intval($this->match_id) > 0 )  {

                //-- Update
                $query = " UPDATE matches set ";
                $query.= " match_date_time='".$db->escape($this->getMatchDateTime())."', ";
                $query.= " venue_id='".$db->escape($this->getVenueId())."',";
                $query.= " player_count='".$db->escape($this->getPlayerCount())."',";
                $query.= " winning_team_id='".$db->escape($this->getWinningTeamId())."',";
                $query.= " tied='".$db->escape($this->getTied())."', ";
                $query.= " status='".$db->escape($this->getStatus())."' ";
                $query.= " where match_id='".$db->escape($this->match_id)."' ";

                $db->execute($query);
            }else{

                $this->created_date = date("Y-m-d H:i:s");

                $query = "INSERT INTO matches (match_date_time,venue_id, player_count, created_date,status) VALUES ";
                $query.= "('";
                $query.= $db->escape($this->getMatchDateTime());
                $query.= "','".$db->escape($this->getVenueId());
                $query.= "','".$db->escape($this->getPlayerCount());
                $query.= "','".$this->created_date;
                $query.= "','".$this->status;
                $query.= "')";

                $new_id = $db->execute($query,$devnull,true);

                $this->match_id = $new_id;
            }
        }catch(\Exception $e) {
            throw $e;
        }


    }

    public static function getMatchById($match_id) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from matches where match_id='".$db->escape($match_id)."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function addUserToMatch($match_id,$user_id) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "insert into match_players (match_id,user_id,created_date) VALUES ";
            $query .= "('".$match_id."','".$user_id."','".self::now()."')";

            $match_player_id = $db->execute($query);

            return $match_player_id;
        }catch(\Exception $e) {

            throw $e;
        }
    }

    public static function getAllMatchPlayers($match_id) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from match_players where match_id='".$match_id."'";

            $match_players = $db->executeAndFetch($query);

            return $match_players;
        }catch(\Exception $e) {

            throw $e;
        }
    }

    public static function getMatchByDateRange($date_from,$date_to) {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from matches where match_date_time BETWEEN '".$date_from."' AND '".$date_to."'";

            return self::createFromDb($db->executeAndFetchSingle($query));

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getAll($from=0,$to=0,$filters=[]) {

    }

    private static function createFromDb($resource) {


        if(!is_null($resource)) {

            $match = new Match();
            $match->setMatchId($resource["match_id"]);
            $match->setMatchDateTime($resource["match_date_time"]);
            $match->setVenueId($resource["venue_id"]);
            $match->setPlayerCount($resource["player_count"]);
            $match->setWinningTeamId($resource["winning_team_id"]);
            $match->setTied($resource["tied"]);
            $match->setCreatedDate($resource["created_date"]);
            $match->setStatus($resource["status"]);

            return $match;
        }

        return null;
    }

    public function toArray() {
        return [
            "match_id"=>$this->match_id,
            "match_date_time"=>$this->match_date_time,
            "venue_id"=>$this->venue_id,
            "player_count"=>$this->player_count,
            "winning_team_id"=>$this->winning_team_id,
            "tied"=>$this->tied,
            "created_date"=>$this->created_date,
            "status"=>$this->status
        ];
    }

    private static function now() {
        return date("Y-m-d H:i:s");
    }

    /**
     * @param $match_id
     * @return \fumbol\common\data\The
     * @throws \Exception
     */
    public static function getMatchTeams($match_id) {

        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query = "select * from match_teams where match_id = '".$match_id."'";

            $result =  $db->executeAndFetch($query);

            return $result;

        }catch(\Exception $e) {
            throw $e;
        }

    }

    public static function addMatchTeam($match_id,$team_name='') {
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "INSERT INTO `match_teams` (`team_name`, `match_id`, `created_date`) VALUES ";
            $query .= "(";
            $query .= "'".$team_name."',";
            $query .= "'".$match_id."',";
            $query .= "'".self::now()."'";
            $query .= ")";

            //echo $query.PHP_EOL;

            $match_team_id = $db->execute($query);

            return $match_team_id;
        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function confirmUserIdInMatchTeam($user_id,$match_id) {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();
            $query  = "UPDATE match_players set confirmed=1 where match_id='".$match_id."' and user_id = '".$user_id."'";
            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function unConfirmUserIdInMatchTeam($user_id,$match_id) {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();
            $query  = "UPDATE match_players set confirmed=0 where match_id='".$match_id."' and user_id = '".$user_id."'";
            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function deleteUserInMatchTeam($user_id,$match_id) {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();
            $query  = "delete from match_players where match_id='".$match_id."' and user_id = '".$user_id."'";
            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function assignMatchTeamIdToPlayers($match_id,$match_team_id,$list_of_user_ids){
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "UPDATE match_players set match_team_id='".$match_team_id."' where match_id='".$match_id."' and user_id in (".implode(",",$list_of_user_ids).")";
            //echo $query.PHP_EOL;
            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function deleteTeamsForMatch($match_id){
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "delete from match_teams where match_id='".$match_id."'";

            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function clearMatchTeamsInMatch($match_id){
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "UPDATE match_players set match_team_id=NULL where match_id='".$match_id."'";

            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function flagMatchPlayer($match_id,$user_id,$flag=1){
        //-- Find by Id
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "UPDATE match_players set flag=$flag where match_id='".$match_id."' and user_id=".$user_id;

            $db->execute($query);

        }catch(\Exception $e) {
            throw $e;
        }
    }

    public static function getFlaggedPlayers($match_id,$flag=1) {
        try {
            $db = (new DBConnectionFactory())->getFumbolDataAccess();

            $query  = "select * from match_players where match_id='".$match_id."' and flag=".$flag;

            $result =  $db->executeAndFetch($query);

            return $result;

        }catch(\Exception $e) {
            throw $e;
        }
    }

}