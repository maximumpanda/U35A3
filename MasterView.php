<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/19/2017
 * Time: 5:42 PM
 */
class MasterView
{
    public static $Layout;

    function __construct()
    {
        self::$Layout = $_SERVER["DOCUMENT_ROOT"] . "/Views/Shared/_Layout.php";
    }

    public static function GenerateView($Path){
        $file = $_SERVER['DOCUMENT_ROOT'] . "/Views/" . implode("/", $Path);
        if (file_exists($file)){
            Session::SetView($file);
            include_once self::$Layout;
            return;
        }
        Helper::Print($file);
        //RouteTable::ReDirectError(404);
    }
}