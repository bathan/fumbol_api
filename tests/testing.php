<?php
//include_once '../include/config.php';

$arr = array();
$arr = [
    1  => 'Orion',
    9  => 'Palermo',
    10 =>'Riquelme',
];

define('_VENUES','{
                              "1": {
                                "name": "25 de Mayo",
                                "default-hour": "22:15",
                                "max-players" : 10,
                                "min-players" : 8
                              },
                              "2": {
                                "name": "BANADE",
                                "default-hour": "23:00",
                                "max-players" : 12,
                                "min-players" : 8
                              }
                            }');

define('_MATCH_DAYS','{
                              "1": {
                                "venue_id": "1"
                              },
                              "4": {
                                "venue_id": "2"
                              },
                              "5": {
                                "venue_id": "1"
                              }
                            }' );


/*

DOW             VENUE_ID
1               1
4               2

$arr[1] = el 25
$arr[4] = banade
$arr[5] = el 25

 */

$day_of_the_week = 9;//date('w');

//var_dump($day_of_the_week);
$match_dates = json_decode(_MATCH_DAYS,true);

@$partido_de_hoy = $match_dates[$day_of_the_week];

var_dump($partido_de_hoy);


foreach($match_dates as $key=>$info) {
    //echo "CHECKEANDO $key  ".PHP_EOL;
    if($key==$day_of_the_week) {
        echo "HAY PARTIDO HOY".PHP_EOL;
        break;
    }
}


//echo "hola".PHP_EOL;

//var_dump($match_dates);

$day_of_the_week = date('w');
@$match_day_info = $match_dates[$day_of_the_week];




/*

$mesa_marron = new Mesa('marron',12,14);
$mesita_verde = new MesitaRatona();

var_dump($mesita_verde);


class Mesa {

    private $color;
    private $ancho;
    private $largo;

    public function __construct($color,$ancho,$largo)
    {
        $this->color =$color;
        $this->ancho = $ancho;
        $this->largo = $largo;

        echo "Hola soy constructor ".$color.PHP_EOL;
    }

    public function plegar() {
        echo "me plegué".PHP_EOL;
    }
}

class MesitaRatona extends Mesa {

    public function __construct()
    {
        parent::__construct('negra', 5, 5);
    }

}
*/