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
        $result = RouteTable::CheckPathToDestination($path);
        Helper::Print($result);
        if ($result == false) {
            //header("location: .." . self::$DefaultErrorPath);
            exit();
        }
        return $result;
    }

    public static function CheckPathToDestination($path){
        $current = self::$Routes;
        $count = count($path);
        Helper::PrintArray($current);
        for ($i = 0; $i < $count; $i++) {
            Helper::Print("CheckPath");

            if (isset($current[$path[$i]])){
                Helper::Print($path[$i]);
                Helper::PrintArray($current[$path[$i]]);
                if ($_SERVER["REQUEST_METHOD"] == 'GET' && array_key_exists("Get", $current[$path[$i]])){
                    Helper::Print($path[$i+1]);
                    Helper::PrintArray($current[$path[$i]]);
                    if (isset($current[$path[$i]]["Get"][$path[$i+1]])){
                        return true;
                    }
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