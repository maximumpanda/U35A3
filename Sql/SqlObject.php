<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 9:35 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/Sql/Sql.php';
class SqlObject
{
    public $Fields = [];

    public function __construct()
    {
    }

    public function AddField(SqlType $field){
        $this->Fields[$field->Alias] = $field;
    }

    public function Clone(){
        $newObject = new SqlObject();
        foreach ($this->Fields as $field){
            $newObject->AddField(clone $field);
        }
        return $newObject;
    }

    public function Query($where = ""){
        $query = 'Select * From ' . reset($this->Fields)->TableName;
        if ($where !== "") $query = $query. ' where ' . $where;
        return Sql::Query($query, $this);
    }

    public function Print(){
        print "<ol style='list-style-type:none'>[";
        foreach ($this->Fields as $field){
            $field->Print();
        }
        print "]</ol>";
    }
}