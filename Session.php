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
}