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
        $query = "Select id, DeliveryTime, Weight, Status.Status AS Status From Packages ".
                 "LEFT JOIN Status On Packages.Status = Status.Id ".
                 "where Id =" . Session::$Bag['PackageId'];
        Helper::Print($query);
        $res = Sql::Query($query);
        Helper::PrintArray($res);
        Session::$Bag['Status'] = "Success";
    }


    public static function AuthenticationLevel()
    {
        return 0;
    }
}