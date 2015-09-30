<?php

namespace fumbol\common;

class Utilities {


    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) != false;
    }

    public static function now() {
        return date("Y-m-d H:i:s");
    }

}