<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 9:35 PM
 */
class SqlObject
{
    public $Name = "";
    public $Fields = [];

    public function __construct($name = "")
    {
        $this->Name = $name;
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

    public function Print(){
        print "<ol style='list-style-type:none'>[";
        foreach ($this->Fields as $field){
            $field->Print();
        }
        print "]</ol>";
    }
}