<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 5:06 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Models/FormsViewModel.php";
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
        $collection = Sql::GetAllFromTable($table)->Members;
        $model = new FormsViewModel();
        $model->Table= $table;
        $model->Collection = $collection;
        foreach ($collection[0]->Fields as $field){
            array_push($model->Fields, $field->alias);
        }
        return $model;
    }
    public static function GetModify(){

    }
}