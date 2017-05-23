<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 4:55 PM
 */

class Router{
    public static $DefaultPath = "/home/index";
    public static $DefaultView = "index";
    public static $DefaultErrorPath = "/error/index";

    public static function ReDirectError($code, $message = ""){
        Session::$Bag["Code"] = $code;
        Session::$Bag["ErrorMessage"] = $message;
        header("location: " . Helper::GetBaseUrl() . self::$DefaultErrorPath);
        exit();
    }

    public static function ReDirectIncomplete($path){
        array_push($path, self::$DefaultView);
        header("location: " . Helper::GetBaseUrl() . "/" . implode("/", $path));
        exit();
    }
}