<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:21 PM
 */
class Helper
{
    public static function Print($message){
        print ("<div>" . $message . "</div>");
    }

    public static function PrintArray($array){
        print ("<pre>" . print_r($array, true) . "</pre>");
    }

    public static function GetClassName($filename) {
        $lastSlash = strrpos($filename, "/");
        if ($lastSlash == false) return false;
        $lastDot = strrpos($filename, ".");
        if ($lastDot == false) return false;
        return substr($filename, $lastSlash + 1, $lastDot - $lastSlash -1);
    }

    public static function GetBaseUrl(){
        if (isset($_SERVER["https"])){
            $protocol = ($_SERVER['https'] && $_SERVER["HTTPS"] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function ArrayKeysToLower($array){
        $newArray = [];
        Helper::PrintArray($array);
        foreach ($array as $key => $value){
            $newArray[strtolower($key)] = $value;
        }
        Helper::PrintArray($newArray);
        Helper::Print("lower keys done");
        return $newArray;
    }
}