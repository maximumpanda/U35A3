<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:17 PM
 */
class Session{
    public Static $Bag = [];
    public static $View;

    public static function SetView($val){
        self::$View = $_SERVER['DOCUMENT_ROOT'] . $val;
    }
    public static function SetParams($paramsString){
        $params = substr($paramsString, 1);
        $splitParams = explode("&", $params);
        foreach ($splitParams as $param){
            $splitParam = explode("=", $param);
            Session::$Bag[$splitParam[0]] = $splitParam[1];
        }
    }
}