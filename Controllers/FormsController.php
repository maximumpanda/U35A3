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
        $list = new SqlCollection();
        $list = Sql::GetTables("u35a1");
        $output = [];
        foreach ($list->Members as $object){
            $table = $object->Fields['name']->Value;
            array_push($output, $table);
        }
        return $output;
    }

    public static function GetView(){
        $table = Session::$Bag["table"];
    }
    public static function GetModify(){
        
    }
}