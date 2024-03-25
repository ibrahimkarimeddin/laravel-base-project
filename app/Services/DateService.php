<?php


namespace App\Services ;
use DateTime;




class  DateService {



    public static function GetTheTimeBetweenTwoDatePerMinutes($first_date , $second_date){

        $start_date = new DateTime($first_date);
        $since_start = $start_date->diff(new DateTime($second_date));
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
         $minutes += $since_start->i;
       $minutes += $since_start->s /60;

        return $minutes ;
    }
    public static function GetTheTimeBetweenTwoDatePerSecond($first_date , $second_date){

        $start_date = new DateTime($first_date);
        $since_start = $start_date->diff(new DateTime($second_date));
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
         $minutes += $since_start->i;
       $minutes += $since_start->s /60;


        return $minutes ;
    }
}
