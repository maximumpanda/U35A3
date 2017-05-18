<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:20 PM
 */
include_once 'RouterHelper.php';
include_once 'RouteTable.php';
include_once 'Controllers/MasterController.php';
$uri = $_SERVER['REQUEST_URI'];
if (strpos(end($uri), ".php") == false) $uri = $uri . "Index.php";
$path = RouterHelper::GetPath($uri);
new MasterController($path);