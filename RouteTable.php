<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/MasterController.php";
class RouteTable
{
    public static $Default = "Home/Index";
    public static $DefaultError = "Error/Index/?code=404";
    public static $Routes = [];

    public static $HiddenBranches = [
        "Api",
        "Error"
    ];

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