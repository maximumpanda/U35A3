<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:21 PM
 */
class Helper
{
    public static function GetPath($uri){
        $path = explode("/", $uri);
        $count = count($path)-1;
        if ( strpos($path[$count], '?'))
            $path[$count] = substr($path[$count], 0, strpos($path[$count], "?"));

        return array_filter($path);
    }
    public static function Print($message){
        print ("<div>" . $message . "</div>");
    }
}