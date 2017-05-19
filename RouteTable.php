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
            header("location: " . Helper::GetBaseUrl() . self::$DefaultErrorPath . self::$Message);
            exit();
        }
        if ($result == 0){
            array_push($path, self::$DefaultView);
            header("location: " . Helper::GetBaseUrl() . "/" . implode("/", $path));
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
                if (self::CheckMethodExists($current, $path[$i], $path[$i+1])){
                    return 1;
                }
                $current = $current[$path[$i]];
            }
        }
        self::$Message =  implode( "_", $path);
        return -1;
    }

    private static function CheckMethodExists($array, $controllerKey, $viewKey){
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (isset($array[$controllerKey][$requestMethod][$viewKey])){
            return true;
        }
        return false;
    }
}