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

print $_SERVER['REQUEST_URI'];
$path = RouterHelper::GetPath("test");
new MasterController($path);