<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/MasterView.php";


class MasterController{
    private $Path = [];
    private $controllers = [];

    function __construct($path) {
        $this->GetControllers();
        foreach ($this->controllers as $file){
            include_once $file;
        }
        $this->ReadParams();
        $this->Path = Helper::ArrayValuesToLower($path);
        RouteTable::GenerateRouteTable($this->controllers);
        $this->BuildView();
    }

    private function BuildView(){

        RouteTable::ValidatePath($this->Path);
        $this->CallController();
        MasterView::GenerateView($this->Path);
    }

    private function GetControllers(){
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/Controllers/";
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $path){
            if (strpos($path, ".php")) array_push($this->controllers, $path);
        }
    }

    private function CallController(){
        $count = count($this->Path);
        $controller = $this->FindController($this->Path[$count-2]);
        $method = $this->FindMethod($controller, end($this->Path));
        Session::$Model = call_user_func($controller."::".$method);
    }

    public function FindMethod($class, $name){
        $methods = (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method){
            if (strtolower($method->name) == strtolower(Helper::GetRequestMethod().$name))
                return $method->name;
        }
        return false;
    }

    private function FindController($name){
        foreach ($this->controllers as $controller){
            $pathParts = pathinfo($controller);
            if (strtolower($pathParts['filename']) == strtolower($name."controller")){
                return $pathParts['filename'];
            }
        }
        return false;
    }

    private function ReadParams() {
        if (strpos(end($this->Path), "?" ) != false){
            $exploded = explode("?", end($this->Path));
            Session::SetParams(end($exploded));
            $count = count($this->Path);
            $this->Path[$count-1] = $exploded[0];
            array_filter($this->Path);
        }
    }
}