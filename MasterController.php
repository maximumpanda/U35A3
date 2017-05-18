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
    private $Path;
    private $controllers;

    function __construct($path)
    {
        $this->controllers = glob($_SERVER['DOCUMENT_ROOT'] . "/Controllers/*.php");
        foreach ($this->controllers as $file){
            include_once $file;

        }
        RouteTable::$Routes = $this->GenerateRouteTable();
        $this->Path  = $path;
        $this->BuildView();
    }

    private function BuildView(){
        if ($this->Path == "") {
            $objData = RouteTable::$Routes["Home"]["Home"];
        }
        else {
            $objData = RouteTable::PathToDestination($this->Path);
        }
        if ($objData == null) {
            $this->Path = "/Error/Index";
        }
        $this->CallController();
        Session::SetView($this->Path);
    }

    public function GenerateRouteTable(){
        $table = [];
        foreach ($this->controllers as $controller){
            $controllerName = Helper::GetClassName($controller);
            $base = $this->GetControllerBaseName($controllerName);
            $methods =  get_class_methods($controllerName);
            $gets = [];
            $posts = [];
            Helper::Print($controllerName);
            Helper::Print(count($methods));
            foreach ($methods as $methodName){
                if (strpos($methodName, "Get") == true) array_push($gets, $methodName);
                if (strpos($methodName, "Post") == true) array_push($posts, $methodName);
            }
            $table[$base] = [
                "Controller" => $controllerName,
                "Get" => $gets,
                "Post" => $posts
            ];
        }
        print_r($table);
        return $table;
    }

    private function GetControllerBaseName($name){
        $controllerText = strpos($name, "Controller");
        return substr($name, 0, $controllerText);
    }

    private function CallController(){
        $count = count($this->Path);
        $controller = $this->Path[$count-2] . "Controller";
        call_user_func($controller."::".$this->Path[$count-1]);
    }
}