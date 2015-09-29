<?php
namespace fumbol\common\data;

/**
 * Class to connect to the Dabases supported by Photofeed base code
 * Author: Doctor Blecker (Before was Arthur Brown)
 */
class DBConnectionFactory {

    public function getFumbolDataAccess() {
        return new FumbolDataAccess();
    }

}
