<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/MasterController.php";
class RouteTable
{
    public static $DefaultPath = "/Home/Index";
    public static $DefaultView = "Index";
    public static $DefaultErrorPath = "/Error/Index/?code=404";
    public static $Routes = [];

    public static $HiddenBranches = [
        "Api",
        "Error"
    ];

    public static function ValidatePath($path){
        Helper::PrintArray($path);
        if ($path == "") {
            //header("location: .." . self::$DefaultPath);
            exit();
        }
        else {
            $result = RouteTable::CheckPathToDestination($path);
        }
        if ($result == false) {
            //header("location: .." . self::$DefaultErrorPath);
            exit();
        }
        return $result;
    }

    public static function CheckPathToDestination($path){
        $current = self::$Routes;
        $count = count($path);
        for ($i = 0; $i < $count; $i++) {
            if (isset($current[$i])){
                if ($_SERVER["REQUEST_METHOD"] == 'GET' && array_key_exists("Get", $current[$i])){
                    if (isset($current[$i]["Get"][$path[$i+1]])){
                        return true;
                    }
                    else
                        return false;
                }
                else if ( $_SERVER["REQUEST_METHOD"] == 'POST' && array_key_exists("Post", $current[$i])){
                    if (isset($current[$i]["Post"][$path[$i+1]])){
                        return true;
                    }
                    else
                        return false;
                }
                else
                    $current = $current[$i];
            }
            else{
                return false;
            }
        }
        return false;
    }
}