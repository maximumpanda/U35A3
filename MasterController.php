<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Session.php";
foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/Controllers/*.php") as $file){
    include_once $file;
}
class MasterController{
    private $Path;

    function __construct($path)
    {
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

    private function CallController(){
        $count = count($this->Path);
        $controller = $this->Path[$count-2] . "Controller";
        call_user_func($controller."::".$this->Path[$count-1]);
    }
}