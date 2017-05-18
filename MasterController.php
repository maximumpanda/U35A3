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
    private $controllers = [];

    function __construct($path)
    {
        $this->GetControllers();
        foreach ($this->controllers as $file){
            include_once $file;
        }
        RouteTable::$Routes = $this->GenerateRouteTable();
        $this->Path = $path;
        $this->ReadParams();
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
        foreach ($this->controllers as $controller){
            $table = array_merge($table, $this->GenerateRouteTableElement($controller));
        }
        return $table;
    }

    private function GenerateParentList($controllerPath){
        $lastDot = strrpos($controllerPath, ".");
        $prefixLength =  strlen($_SERVER['DOCUMENT_ROOT'] . "/Controllers/");
        $relativePath = substr($controllerPath, $prefixLength , $lastDot - $prefixLength);
        $list = array_filter(explode("/", $relativePath));
        array_pop($list);
        return $list;
    }

    private function GenerateRouteTableElement($controller){
        $parentList = $this->GenerateParentList($controller);
        $controllerName = Helper::GetClassName($controller);
        $base = $this->GetControllerBaseName($controllerName);
        $methods = (new ReflectionClass($controllerName))->getMethods(ReflectionMethod::IS_PUBLIC);
        $gets = [];
        $posts = [];
        $element = [];
        foreach ($methods as $method){
            if ( strpos($method->name, "Get") !== false) array_push($gets, substr($method->name, 3));
            if (strpos($method->name, "Post") !== false) array_push($posts, substr($method->name, 4));
        }
        $element[$base] =[
            "Controller" => $controllerName,
            "Get" => $gets,
            "Post" => $posts
        ];
        $parentListSize = count($parentList);
        for ($i = $parentListSize-1; $i >= 0; $i--){
            $newElement = [ $parentList[$i] => $element];
            $element = $newElement;
        }
        return $element;
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

    private function ReadParams()
    {
        if (end($this->Path)[0] == "?"){
            Session::SetParams(end($this->Path));
            array_pop($this->Path);
        }
        Helper::PrintArray($this->Path);
        Helper::PrintArray(Session::$Bag);
    }
}