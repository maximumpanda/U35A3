<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 2:37 PM
 */
class SqlTable
{
    public $Name;
    public $PrimaryKey;
    Public $Members = [];

    function __construct($name, $key = "", $members = [])
    {
        $this->Name = $name;
        $this->PrimaryKey = $key;
        $this->Members = $members;
    }

    public function Print(){
        print "<ol style='list-style-type:none'>{$this->Name}[";
        foreach ($this->Members as $val){
            $val->Print();
        }
        print "]</ol>";
    }
}