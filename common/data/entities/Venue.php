<?php

namespace fumbol\common\data\entities;

class Venue {

    private $venue_id;
    private $venue_name;
    private $venue_default_hour;
    private $min_players;
    private $max_players;

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
    public function getVenueName()
    {
        return $this->venue_name;
    }

    /**
     * @param mixed $venue_name
     */
    public function setVenueName($venue_name)
    {
        $this->venue_name = $venue_name;
    }

    /**
     * @return mixed
     */
    public function getVenueDefaultHour()
    {
        return $this->venue_default_hour;
    }

    /**
     * @param mixed $venue_default_hour
     */
    public function setVenueDefaultHour($venue_default_hour)
    {
        $this->venue_default_hour = $venue_default_hour;
    }

    /**
     * @return mixed
     */
    public function getMinPlayers()
    {
        return $this->min_players;
    }

    /**
     * @param mixed $min_players
     */
    public function setMinPlayers($min_players)
    {
        $this->min_players = $min_players;
    }

    /**
     * @return mixed
     */
    public function getMaxPlayers()
    {
        return $this->max_players;
    }

    /**
     * @param mixed $max_players
     */
    public function setMaxPlayers($max_players)
    {
        $this->max_players = $max_players;
    }

    public static function getById($venue_id) {
        //-- Find by Id
        try {
            $data_source = json_decode(_VENUES,true);
            return self::createFromDb($venue_id,$data_source[$venue_id]);

        }catch(\Exception $e) {
            throw $e;
        }
    }




    private static function createFromDb($venue_id,$resource) {


        if(!is_null($resource)) {

            $venue = new Venue();
            $venue->setVenueId($venue_id);
            $venue->setVenueName($resource["name"]);
            $venue->setVenueDefaultHour($resource["default-hour"]);
            $venue->setMaxPlayers($resource["max-players"]);
            $venue->setMinPlayers($resource["min-players"]);

            return $venue;
        }

        return null;
    }

    public function toArray() {
        return [
            "venue_id"=>$this->venue_id,
            "venue_name"=>$this->venue_name,
            "venue_default_hour"=>$this->venue_default_hour,
            "max_players"=>$this->max_players,
            "min_players"=>$this->min_players
        ];
    }

}