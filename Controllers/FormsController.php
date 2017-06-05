<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 5:06 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Models/Forms/View.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Forms/Form.php';
class FormsController
{
    public static function GetIndex(){
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
        $model = new View();
        $model->Table= $table;
        $model->Collection = $collection;
        foreach ($collection->Members[0]->Fields as $field){
            array_push($model->Fields, $field->Alias);
        }
        return $model;
    }
    public static function GetModify(){
        $model = Sql::GenerateModel(Session::$Bag['Table'], true);
        $res = $model->SelectAll('Id=' . Session::$Bag['Id']);
        foreach ($model->Fields as $key => $value){
            $model->Fields[$key]->Value = $res->Members[0]->Fields[$key]->Value;
        }
        $form = Form::NewFromModel($model);
        return $form;
    }
    public static function PostModify(){
        Helper::PrintArray($_POST);
        $id = -1;
        $values = [];
        foreach ($_POST as $key => $value){
            if ($key == "Id"){
                $id = $value;
            }
            else{
                $values[$key] = $value;
            }

        }
        $query = 'Update ' . Session::$Bag['Table'] .
                 ' Set ';
        foreach ($values as $key => $value){
            $query .= $key . " = " . Sql::ParametrizeValue($value) . ",";
        }
        $query = substr($query, 0, strlen($query)-1);
        Helper::PrintArray($query);
        exit();
        Router::Redirect("/Forms/Result?Action=Modify&Status=Success");
    }
    public static function GetAdd(){
        $table = Session::$Bag['Table'];
        $model = Sql::GenerateModel($table, true);
        $form = Form::NewFromModel($model);
        return $form;

    }
    public static function GetResult(){
        $model =[
            "Action" => Session::$Bag['Action'],
            "Status" => Session::$Bag['Status']
        ];
        return $model;
    }

    public static function PostAdd(){
        Router::Redirect("/Forms/Result?Action=Add&Status=Success");
    }

    private static function GenerateFormSchema($table){
        $sqlSchema = Sql::GenerateModel($table);
        $model = new Form();

    }
}