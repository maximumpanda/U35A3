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
    public $Type;
    public $Length;
    public $Unsigned;
    public $Nullable;
    public $IsKey;
    public $ForeignTable = "";
    public $ForeignColumn = "";
    public $AutoIncrement;
    public $Value;

    function __construct($row, $foreignTable = "", $foreignColumn = "")
    {
        $this->Name = $row['Field'];
        $this->Type = self::ParseType($row['Type']);
        $this->Length = self::ParseLength($row['Type']);
        $this->Unsigned = strpos($row['Type'], 'unsigned') !== false;
        $this->Nullable = $row['Null'] !== 'NO';
        $this->IsKey = $row['Key'] !== "";
        $this->ForeignTable = $foreignTable;
        $this->ForeignColumn = $foreignColumn;
        $this->AutoIncrement = strpos($row['Extra'], "auto_increment") !== false;
    }

    public function Print(){
        print "<ul style='list-style-type: none'>{$this->Name} [";
        print "<ul>" . "Type: " . $this->Type . ",</ul>";
        print "<ul>" . "Length: " . $this->Length . ",</ul>";
        print "<ul>" . "Unsigned: " . $this->Unsigned . ",</ul>";
        print "<ul>" . "Nullable: " . $this->Nullable . ",</ul>";
        print "<ul>" . 'IsKey: ' . $this->IsKey . ",</ul>";
        if ($this->ForeignTable != "") print "<li>" . "FKTable: " . $this->ForeignTable . ",</li>";
        if ($this->ForeignColumn != "") print "<li>" . "FKCol: " . $this->ForeignColumn. ",</li>";
        print "<ul>" . "AI: " . $this->AutoIncrement . "</ul>";
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