<?php

namespace fumbol\common;

use fumbol\common\data\entities\Venue;

class MatchUtilities {


    public static function addMoreInfoToMatch(&$matchArray)
    {
        //-- Add Venue Info
        $venue_id = $matchArray["venue_id"];
        $venue_info = Venue::getById($venue_id);
        $matchArray["venue"] = $venue_info->toArray();
        return $matchArray;
    }



}