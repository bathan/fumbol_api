<?php

namespace fumbol\common;

use fumbol\common\data\entities\User;

class UserUtilities {


    public function getUserInfoFromToken($user_token) {

        $token_info = Utilities::parse_signed_request($user_token,_ENCODING_SECRET);

        if(is_null($token_info)) {
            throw new \Exception("Invalid token");
        }
        //-- TODO:: Token expiration?

        $user_id = $token_info["user_id"];
        $user_info = User::getByUserId($user_id);
        return $user_info;

    }



}