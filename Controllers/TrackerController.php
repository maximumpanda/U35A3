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

    public static function PostTrack(){
        Helper::PrintArray($_POST);
        Router::Redirect('/Tracker/Result?Status="At Depot"');
    }

    public static function GetResult(){
        $model =[
            "Status" => Session::$Bag['Status']
        ];
        return $model;
    }

    public static function AuthenticationLevel()
    {
        return 0;
    }
}