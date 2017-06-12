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
        $model = Sql::GenerateModel("Packages");
        Helper::PrintArray($model);
        Session::$Bag['Status'] = "Success";
    }


    public static function AuthenticationLevel()
    {
        return 0;
    }
}