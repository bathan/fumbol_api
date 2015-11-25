<?php

namespace fumbol\common;

class Language
{

    private static function getDictionary() {
        $json_file = _APP_PATH."/lang/"._DEFAULT_LANGUAGE."/dic.json";
        $json_contents = json_encode(file_get_contents($json_file),true);
        return $json_contents;
    }

    public static function t($k,$default=null) {
        $dic = self::getDictionary();
        $val = isset($dic[$k]) ? $dic[$k] : $default;
        return $val;
    }
}