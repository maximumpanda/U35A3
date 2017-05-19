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
        if ($result == -1) {
            //header("location: " . Helper::GetBaseUrl() . self::$DefaultErrorPath . self::$Message);
            exit();
        }
        if ($result == 0){
            $path = array_push($path, self::$DefaultView);
            Helper::PrintArray($path);
            //header("location: " . Helper::GetBaseUrl() . "/" . implode("/", $path));
        }
        return $result;
    }

    public static function CheckPathToDestination($path){
        $current = self::$Routes;
        $count = count($path);
        for ($i = 0; $i < $count; $i++) {
            Helper::Print("looped");
            Helper::PrintArray($path);
            Helper::PrintArray($current);
            if (isset($current[$path[$i]])){
                if ($i+1 >= $count){
                    Helper::Print("incomplete");
                    return 0;
                }
                if ($_SERVER["REQUEST_METHOD"] == 'GET' && array_key_exists("Get", $current[$path[$i]])){
                    if (isset($current[$path[$i]]["Get"][$path[$i+1]])){
                        return 1;
                    }
                }
                else if ( $_SERVER["REQUEST_METHOD"] == 'POST' && array_key_exists("Post", $current[$i])){
                    if (isset($current[$i]["Post"][$path[$i+1]])){
                        return 1;
                    }
                }

                $current = $current[$path[$i]];
            }
        }
        Helper::Print("Failed");
        self::$Message =  implode( "_", $path);
        return -1;
    }
}