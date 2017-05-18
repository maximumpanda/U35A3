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

print $_SERVER['argc'];
print_r($_SERVER['argv']);
$path = RouterHelper::GetPath("test");
new MasterController($path);