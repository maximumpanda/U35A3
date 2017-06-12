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
    /** @var SqlType[] $Fields */
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

    public function SelectAll($where = ""){

        if($where !== "") {
            $query = 'SELECT * FROM ' . reset($this->Fields)->TableName;
            if ($where !== "") $query = $query . ' where ' . $where;
            return Sql::Query($query);
        }
        else{
            $res = Sql::GetAllFromTable(reset($this->Fields)->TableName);
            return $res;
        }
    }

    public function Select($elements = "*", $where = ""){
        $query = 'Select ' . $elements . ' From ' . reset($this->Fields)->TableName;
        if ($where !== "") $query = $query . ' where ' . $where;
        return Sql::Query($query);
    }

    public function Summarize(){
        $summary = [];
        $values = [];
        Helper::PrintArray($this->Fields);
        foreach ($this->Fields as $field){
            if ($field->KeyType == 1) continue;
            if ($field->KeyType == 2){
                array_push($values, Sql::GetLinkedValues($field->TableName, $field->Value));
            }
            else {
                array_push($values, $field->Value);
            }
        }
        $summary[$this->Fields['Id']->Value] = implode(", ", $values);
        return $summary;
    }

    public function Print(){
        print "<ol style='list-style-type:none'>[";
        foreach ($this->Fields as $field){
            $field->Print();
        }
        print "]</ol>";
    }
}