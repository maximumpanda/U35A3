<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:17 PM
 */
session_start();
Session::$Bag =& $_SESSION;
if (!isset(Session::$Bag['AuthenticationLevel'])) Session::$Bag['AuthenticationLevel'] = 0;
class Session{
    public Static $Bag;
    public static $View;
    public static $Model;

    public static function SetView($val){
        self::$View = $val;
    }
    public static function SetParams($paramsString){
        $splitParams = explode("&", $paramsString);
        foreach ($splitParams as $param){
            $splitParam = explode("=", $param);
            Session::$Bag[$splitParam[0]] = $splitParam[1];
        }
    }
}