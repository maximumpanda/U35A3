<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/19/2017
 * Time: 5:42 PM
 */
class MasterView
{
    public static $Layout;

    function __construct()
    {
        self::$Layout = $_SERVER["DOCUMENT_ROOT"] . "/Views/Shared/_Layout.php";
    }

    public static function GenerateView($path){
        $view = self::ViewExists($path);
        Helper::PrintArray($view);
        if ($view != false){
            Session::SetView($view);
            include_once self::$Layout;
            return;
        }
        Helper::Print($view);
        //RouteTable::ReDirectError(404);
    }

    public static function ViewExists($path){
        $file = $_SERVER['DOCUMENT_ROOT'] . "/Views";
        $currentDir = $file;

        foreach ($path as $item){
            $files = glob($currentDir .'/*', GLOB_NOSORT);
            $itemName = strtolower($item);
            $found = false;
            foreach ($files as $file){
                if (strtolower($file) == $itemName || strtolower($file) == $itemName.".html") {
                    $currentDir = $currentDir . "/" . $file;
                    $found = true;
                    continue;
                }
            }
            if ($found == false)
                return false;
        }
        return $currentDir;
    }
}