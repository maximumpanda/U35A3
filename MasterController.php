<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Session.php";


class MasterController{
    private $Path = [];
    private $controllers;

    function __construct($path)
    {
        $this->GetControllers();
        $this->controllers = glob($_SERVER['DOCUMENT_ROOT'] . "/Controllers/*.php");
        foreach ($this->controllers as $file){
            include_once $file;
        }
        $this->Path = $path;
        RouteTable::$Routes = $this->GenerateRouteTable();
        $this->BuildView();
    }

    private function BuildView(){
        if ($this->Path == "") {
            $objData = RouteTable::$DefaultPath;
        }
        else {
            $objData = RouteTable::PathToDestination($this->Path);
        }
        if ($objData == null) {
            $objData = RouteTable::$DefaultErrorPath;
        }
        $this->Path = $objData;

        $this->CallController();
        Session::SetView($this->Path);
    }

    public function GenerateRouteTable(){
        $table = [];
        Helper::PrintArray($this->controllers);
        foreach ($this->controllers as $controller){
            array_push($table, $this->GenerateRouteTableElement($controller));
        }
        Helper::PrintArray($table);
        return $table;
    }

    private function GenerateParentList($controllerPath){
        $lastDot = strrpos($controllerPath, ".");
        $prefixLength =  strlen($_SERVER['DOCUMENT_ROOT'] . "/Controllers/");
        $relativePath = substr($controllerPath, $prefixLength , $lastDot - $prefixLength);
        Helper::Print($relativePath);
        return array_filter(explode("/", $relativePath));
    }

    private function GenerateRouteTableElement($controller){
        $parentList = $this->GenerateParentList($controller);
        Helper::PrintArray($parentList);
        $controllerName = Helper::GetClassName($controller);
        $base = $this->GetControllerBaseName($controllerName);
        $methods = get_class_methods($controllerName);
        $gets = [];
        $posts = [];
        $element = [];
        foreach ($methods as $methodName){
            if ( strpos($methodName, "Get") !== false) array_push($gets, substr($methodName, 3));
            if (strpos($methodName, "Post") !== false) array_push($posts, substr($methodName, 4));
        }
        if ($parentList != null){
            $last = $element;

            for ($i =0; $i < count($parentList)-1; $i++){
                $last[$parentList[$i]] = [$parentList[$i+1] =>[]];
                $last = $last[0];
            }
            $last[$base] =[
                "Controller" => $controllerName,
                "Get" => $gets,
                "Post" => $posts
            ];

            return $element;
        }
        return false;
    }

    private function GetControllerBaseName($name){
        $controllerText = strpos($name, "Controller");
        return substr($name, 0, $controllerText);
    }

    private function GetControllers(){
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/Controllers/";
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $path){
            if (strpos($path, ".php")) array_push($this->controllers, $path);
        }
    }

    private function CallController(){
        $count = count($this->Path);
        Helper::Print($count);
        Helper::PrintArray($this->Path);
        $controller = $this->Path[$count-2] . "Controller";
        Helper::Print($controller);
        call_user_func($controller."::".$this->Path[$count-1]);
    }
}