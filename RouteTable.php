<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/MasterController.php";
class RouteTable
{
    public static $DefaultPath = ["Home","Index"];
    public static $DefaultErrorPath = ["Error", "Index", "?code=404"];
    public static $Routes = [];

    public static $HiddenBranches = [
        "Api",
        "Error"
    ];

    public static function IsValidPath($path, $current = null, $index =0){
        if ($current == null) $current = self::$Routes;
        if (isset($current[$path[$index]])){
            if ($current[$path[$index]]);
        }
    }

    public static function PathToDestination($path){
        $current = self::$Routes;
        foreach ($path as $val){
            if (isset($current[$val])){
                $current = $current[$val];
            }
            else{
                return null;
            }

        }
        return $current;
    }
}