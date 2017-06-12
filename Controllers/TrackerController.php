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
        unset($model['Clients']);
        unset($model['Handler']);
        unset($model['Recipient']);
        unset($model['Address']);
        $form = Form::NewFromModel($model);
        Helper::PrintArray($model);
        Helper::PrintArray($form->Elements);
        Session::$Bag['Status'] = "Success";
    }


    public static function AuthenticationLevel()
    {
        return 0;
    }
}