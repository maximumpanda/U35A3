<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:30 PM
 */
class TrackerController implements IController
{
    public static function GetIndex(){

    }

    public static function GetTrack(){
        $query = "Select Packages.Id, DeliveryTime, Weight, Status.Status AS Status From Packages ".
                 "LEFT JOIN Status On Packages.Status = Status.Id ".
                 "where Packages.Id =" . Session::$Bag['PackageId'];
        $res = Sql::Query($query);
        if (count($res->Members) > 0){
            return $res->Members[0];
        }
        return null;
    }


    public static function AuthenticationLevel()
    {
        return 0;
    }
}