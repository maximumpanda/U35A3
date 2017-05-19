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
        $this->Path = Helper::ArrayValuesToLower($path);
        $this->ReadParams();
        RouteTable::$Routes = $this->GenerateRouteTable();
        $this->BuildView();
    }

    private function BuildView(){

        RouteTable::ValidatePath($this->Path);
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
        $res = [];
        foreach ($list as $key => $value){
            $res[$key] = strtolower($value);
        }
        return $res;
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
            if ( strpos($method->name, "Get") !== false) $gets[strtolower(substr($method->name, 3))] = $method->name;
            if (strpos($method->name, "Post") !== false) $posts[strtolower(substr($method->name, 4))] = $method->name;
        }
        $element[strtolower($base)] =[
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
        $controller = $this->Path[$count-2] . "Controller";
        call_user_func($controller."::".$this->Path[$count-1]);
    }

    private function ReadParams()
    {
        if (strpos(end($this->Path), "?" ) != false){
            $exploded = explode("?", end($this->Path));
            Session::SetParams(end($exploded));
            $count = count($this->Path);
            $this->Path[$count-1] = $exploded[0];
            array_filter($this->Path);
        }
    }
}