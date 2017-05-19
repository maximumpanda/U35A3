<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:20 PM
 */
include_once $_SERVER["DOCUMENT_ROOT"] . "/Helper.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/RouteTable.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/MasterController.php";
session_start();
$uri = $_SERVER['REQUEST_URI'];
if ($uri == '/'){
    $uri = RouteTable::$DefaultPath;
}
$path = array_values(array_filter(explode("/", $uri)));
new MasterController($path);