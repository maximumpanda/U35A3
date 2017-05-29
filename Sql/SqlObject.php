<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 9:35 PM
 */
class SqlObject
{
    public $Fields = [];

    public function __construct($name = "")
    {
        $this->Name = $name;
    }

    public function AddField(SqlType $field){
        $this->Fields[$field->Alias] = $field;
    }

    public function Print(){
        print "<ol style='list-style-type:none'>[";
        foreach ($this->Fields as $field){
            $field->Print();
        }
        print "]</ol>";
    }
}