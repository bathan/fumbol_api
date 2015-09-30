<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('America/Buenos_Aires');

require_once dirname(__FILE__) . "/config.local.php";
require_once dirname(__FILE__) . '/fumbol_autoload.php';
require_once dirname(__FILE__) . '/../common/data/FumbolDataAccess.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';


$defaultValues = array(
    '_DB_NAME' => 'fumbol',
    '_DB_USER' => 'root',
    '_DB_PASS' => 'revoluti0n',
    '_DB_CONN_ERROR_RETRIES' => 3,
    '_TOKEN_SECRET'=>'AguanteElFumbolVieja',

);

//-- Redefinir con los defaultValues lo que no haya en config.local

foreach ($defaultValues as $name => $val) {
    if (!defined($name))
        define($name, $val);
}

if(defined('_APP_DEBUG') && _APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

function pretty_print($data) {
    foreach($data as $k=>$v) {
        echo "[".$k."] = ".$v."\n";
    }
}

//Encoding Secret
define('_ENCODING_SECRET', 'MiViejaMulaYaNoEsLoQueEra');