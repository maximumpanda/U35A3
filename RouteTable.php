<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/MasterController.php";
class RouteTable
{
    public static $DefaultPath = "/home/index";
    public static $DefaultView = "index";
    public static $DefaultErrorPath = "/error/index?code=404&msg=";
    public static $Message = "";
    public static $Routes = [];
    public static $HiddenBranches = [
        "Api",
        "Error"
    ];

    public static function ValidatePath($path){
        $result = RouteTable::CheckPathToDestination($path);
        Helper::Print($result);
        if ($result == false) {
            //header("location: " . Helper::GetBaseUrl() . self::$DefaultErrorPath . self::$Message);
            exit();
        }
        return $result;
    }

    public static function CheckPathToDestination($path){
        $current = self::$Routes;
        $count = count($path);
        for ($i = 0; $i < $count; $i++) {
            if (isset($current[$path[$i]])){
                if ($_SERVER["REQUEST_METHOD"] == 'GET' && array_key_exists("Get", $current[$path[$i]])){
                    if (isset($current[$path[$i]]["Get"][$path[$i+1]])){
                        return true;
                    }
                }
                else if ( $_SERVER["REQUEST_METHOD"] == 'POST' && array_key_exists("Post", $current[$i])){
                    if (isset($current[$i]["Post"][$path[$i+1]])){
                        return true;
                    }
                }
            }
        }
        self::$Message =  implode( "_", $path);
        return false;
    }
}