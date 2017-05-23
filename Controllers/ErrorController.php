<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:31 PM
 */
include_once $_SERVER["DOCUMENT_ROOT"] . "/Models/ErrorModel.php";
class ErrorController
{
    private static $ErrorCodes = [
        404 => "Page Not Found"
        ];


    public static function GetIndex(){
        $model = new ErrorModel();
        $model->Title = Session::$Bag['Code'];
        $model->Message = "";
        $model->ErrorMessage = self::$ErrorCodes[Session::$Bag['Code']];
        return $model;
    }
}