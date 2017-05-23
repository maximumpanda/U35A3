<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/19/2017
 * Time: 5:42 PM
 */
class MasterView
{

    public static function GenerateView($path){
        $view = self::FindView($path);
        if ($view != false){
            Session::SetView($view);
            include_once $_SERVER["DOCUMENT_ROOT"] . "/Views/Shared/_Layout.php";
            return;
        }
        Router::ReDirectError(404, $view);
    }

    public static function FindView($path){
        $currentDir = $_SERVER['DOCUMENT_ROOT'] . "/Views/";;
        $lastFound = "";
        foreach ($path as $item){
            $files = glob($currentDir ."*");
            $itemName = strtolower($currentDir) . strtolower($item);
            $found = false;
            foreach ($files as $file){
                if (strtolower($file) == $itemName || strtolower($file) == $itemName . ".html") {
                    $currentDir = $currentDir . basename($file). "/";
                    $lastFound = $file;
                    $found = true;
                    break;
                }
            }
            if ($found == false)
                return false;
        }
        return $lastFound;
    }
}