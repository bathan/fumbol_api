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
                $query.= " tied='".$db->escape($this->getTied())."' ";
                $query.= " where match_id='".$db->escape($this->match_id)."' ";

                $db->execute($query);
            }else{

                $this->created_date = date("Y-m-d H:i:s");

                $query = "INSERT INTO matches (match_date_time,venue_id, player_count, created_date) VALUES ";
                $query.= "('";
                $query.= $db->escape($this->getMatchDateTime());
                $query.= "','".$db->escape($this->getVenueId());
                $query.= "','".$db->escape($this->getPlayerCount());
                $query.= "','".$this->created_date;
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
            "created_date"=>$this->created_date
        ];
    }

}