<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:20 PM
 */
include_once 'RouterHelper.php';
include_once 'RouteTable.php';
include_once 'MasterController.php';

$uri = $_SERVER['REQUEST_URI'];
if (strpos($uri, ".php") == false || $uri == '\\'){
    $uri = RouteTable::$Default;
}
$path = RouterHelper::GetPath("test");
$masterController = new MasterController($path);
$masterController->GenerateRouteTable();