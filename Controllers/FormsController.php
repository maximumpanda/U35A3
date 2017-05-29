<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 5:06 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Models/FormsModel.php";
class FormsController
{
    public static function GetIndex(){
        $list = new FormsModel();
        Sql::GetTables("u33a2");
    }
}