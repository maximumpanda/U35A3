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
        print ("<pre>$message</pre>");
    }

    public static function PrintArray($array){
        print ('<pre>' . print_r($array, true) . '</pre>');
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
        foreach ($array as $key => $value){
            $newArray[strtolower($key)] = $value;
        }
        return $newArray;
    }
    public static function ArrayValuesToLower($array){
        $newArray = [];
        foreach ($array as $key => $value){
            $newArray[$key] = strtolower($value);
        }
        return $newArray;
    }

    public static function GetRequestMethod(){
        if ($_SERVER['REQUEST_METHOD'] == "GET")  return "Get";
        if ($_SERVER['REQUEST_METHOD'] == "POST") return"Post";
        if ($_SERVER['REQUEST_METHOD'] == "Delete") return "Delete";
        else
            Router::ReDirectError(405);
    }

    public static function SplitPascalCase($string){
        $caps = [];
        $result = "";
        preg_match('/(A-Z)/', $string, $caps, PREG_OFFSET_CAPTURE);
        Helper::PrintArray($caps);
        for ($i = count($caps)-1; $i >=0; $i--){
            $start = $caps[$i][1];
            if ($i == count($caps)-1){
                    $result = substr($string, $start);
            }
            else{
                $end = $caps[$i+1][1];
                $result = substr($string, $string, $end) . " " . $result;
            }
        }
        return $result;
    }
}