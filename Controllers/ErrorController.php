<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:31 PM
 */
include_once $_SERVER["DOCUMENT_ROOT"] . "/Models/Error/ErrorModel.php";
class ErrorController
{
    private static $ErrorCodes = [
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Page Not Found",
        405 => "MethodNotAllowed"
        ];


    public static function GetIndex(){
        $model = new ErrorModel();
        $model->Title = Session::$Bag['Code'];
        $model->Message = Session::$Bag['Message'];
        $model->ErrorMessage = self::$ErrorCodes[Session::$Bag['Code']];
        Helper::PrintArray(RouteTable::$Routes);
        return $model;
    }
}