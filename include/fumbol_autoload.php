<?php

function _fumbol_autoload_funcs($class) {

    $class = str_replace("fumbol","",$class);
    $path = sprintf(
        '%s/%s.php', __DIR__ . '/..', str_replace("\\", '/', $class)
    );

    //echo $class." : ".$path."\n";

    if (file_exists($path)){
        require_once($path);
    }else{
        return false;
    }
}




spl_autoload_register('_fumbol_autoload_funcs');
