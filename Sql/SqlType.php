<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 12:31 PM
 */
class SqlType
{
    public $Name;
    public $TableName;
    public $Type;
    public $Length;
    public $Unsigned;
    public $Nullable;
    public $IsKey;
    public $ForeignTable;
    public $AutoIncrement;
    public $Value;

    function __construct($row, $tableName, SqlTable $foreignTable = null)
    {
        $this->Name = $row['Field'];
        $this->TableName = $tableName;
        $this->Type = self::ParseType($row['Type']);
        $this->Length = self::ParseLength($row['Type']);
        $this->Unsigned = strpos($row['Type'], 'unsigned') !== false;
        $this->Nullable = $row['Null'] !== 'NO';
        $this->IsKey = $row['Key'] !== "";
        $this->ForeignTable = $foreignTable;
        $this->AutoIncrement = strpos($row['Extra'], "auto_increment") !== false;
    }

    public function Print(){
        print "<ul style='list-style-type:none'>{$this->Name} [";
        print "<ul>" . "Table: " . $this->TableName . ",</ul>";
        print "<ul>" . "Type: " . $this->Type . ",</ul>";
        print "<ul>" . "Length: " . $this->Length . ",</ul>";
        print "<ul>" . "Unsigned: " . $this->Unsigned . ",</ul>";
        print "<ul>" . "Nullable: " . $this->Nullable . ",</ul>";
        print "<ul>" . 'IsKey: ' . $this->IsKey . ",</ul>";
        if ($this->ForeignTable != null) print "<li>" . $this->ForeignTable->Print() . ",</li>";
        print "<ul>" . "Auto-Increment: " . $this->AutoIncrement . "</ul>";
        print "]</ul>";
    }

    private static function ParseType($raw){
        $end = strpos($raw, "(");
        return substr($raw, 0, $end);
    }

    private static function ParseLength($raw){
        $start = strpos($raw, "(")+1;
        $end = strpos($raw, ")");
        return substr($raw, $start,$end-$start);
    }
}