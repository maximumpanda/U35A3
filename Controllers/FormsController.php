<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 5:06 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Models/FormsViewModel.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/Models/FormsObjectFormModel.php';
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
        $table = Session::$Bag["Table"];
        $collection = Sql::GetAllFromTable($table);
        $model = new FormsViewModel();
        $model->Table= $table;
        $model->Collection = $collection;
        foreach ($collection->Members[0]->Fields as $field){
            array_push($model->Fields, $field->Alias);
        }
        return $model;
    }
    public static function GetModify(){
        $model = Sql::GenerateModel(Session::$Bag['Table'], true);
        $res = $model->Query('Id=' . Session::$Bag['Id']);
        Helper::PrintArray($model);
        $res->Print();
    }
    public static function PostModify(){

    }
    public static function GetAdd(){
        $table = Session::$Bag['Table'];

    }
    public static function PostAdd(){

    }

    private static function GenerateFormSchema($table){
        $sqlSchema = Sql::GenerateModel($table);
        $model = new FormsObjectFormModel();

    }
}