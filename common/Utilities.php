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

    public static function today_start() {
        return date("Y-m-d 00:00:00");
    }

    public static function today_end() {
        return date("Y-m-d 23:59:59");
    }

    /**
     * Generates a signed request from a data array
     *
     * @param Array $data
     * @param String $secret
     * @return String
     */
    public static function generate_signed_request($data, $secret) {
        $data['algorithm'] = 'HMAC-SHA256';
        $payload = self::base64_url_encode(json_encode($data));
        $encoded_sig = self::base64_url_encode(hash_hmac('sha256', $payload, $secret, $raw = true));
        return $encoded_sig . '.' . $payload;
    }

    /**
     * Parse and validate a signed request and returns an array
     * with the values contained in it.
     *
     * A parameter of the decoded request must indicate the algorithm.
     *
     * @author Facebook Inc.
     * http://developers.facebook.com/docs/authentication/
     *
     * @param String $signed_request
     * @param String $secret
     * @return Array
     */
    public static function parse_signed_request($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = self::base64_url_decode($encoded_sig);
        $data = json_decode(self::base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private static function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/', '-_');
    }
    private static function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function getPlayersInTeams($all_players,$key_column='match_team_id') {
        $players_in_teams = [];
        foreach ($all_players as $sp) {
            $match_team_id = $sp[$key_column];
            if (!is_null($match_team_id)) {
                //-- This lets us know that we have no teams selected yet.
                $players_in_teams[$sp[$key_column]][] = $sp;
            }
        }
        return $players_in_teams;
    }

    public static function SpanishDate($FechaStamp)
    {
        $ano = date('Y',$FechaStamp);
        $mes = date('n',$FechaStamp);
        $dia = date('d',$FechaStamp);
        $hora = date('G:i',$FechaStamp);

        $diasemana = date('w',$FechaStamp);
        $diassemanaN= array("Domingo","Lunes","Martes","Miércoles",
            "Jueves","Viernes","Sábado");
        $mesesN=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
            "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        return $diassemanaN[$diasemana].", $dia de ". $mesesN[$mes] ." de $ano a las ".$hora;
    }


}