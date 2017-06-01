<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 4:55 PM
 */

class Router{
    public static $DefaultPath = "/Home/Index";
    public static $DefaultView = "Index";
    public static $DefaultErrorPath = "/Error/Index";

    public static function ReDirectError($code, $message = ""){
        Session::$Bag["Code"] = $code;
        Session::$Bag["Message"] = $message;
        header("location: " . Helper::GetBaseUrl() . self::$DefaultErrorPath);
        exit();
    }

    public static function RedirectIncomplete($path){
        array_push($path, self::$DefaultView);
        header("location: " . Helper::GetBaseUrl() . "/" . implode("/", $path));
        exit();
    }

    public static function Redirect($path){
        header('location: ' . Helper::GetBaseUrl() . $path);
        exit();
    }
}