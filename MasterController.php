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
            Helper::Print($controllerName);
            Helper::Print($this->GetControllerBaseName($controllerName));
            $table[$controller] = $controller;
        }
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