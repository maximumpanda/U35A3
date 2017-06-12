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
        unset($model->Fields['Id']);
        unset($model->Fields['Client']);
        unset($model->Fields['Handler']);
        unset($model->Fields['Recipient']);
        unset($model->Fields['Address']);
        $query = Sql::BuildJoinStatement($model, 'Id = '.Session::$Bag['PackageId']);
        Helper::PrintArray($model);
        Helper::PrintArray($query);
        Session::$Bag['Status'] = "Success";
    }


    public static function AuthenticationLevel()
    {
        return 0;
    }
}