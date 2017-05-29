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
    public $OriginalName;
    public $Table;
    public $OriginalTable;
    public $Database;
    public $Type;
    public $Length;
    public $Unsigned;
    public $Nullable;
    public $KeyType;
    public $ForeignTable;
    public $AutoIncrement;
    public $Decimals;
    public $Value;

    public static $TypeCodes = [
        16 => 'bit',
        1 => 'bool',
        2 => 'smallint',
        9 => 'mediumint',
        3 => 'int',
        8 => 'bigint',
        4 => 'float',
        5 => 'double',
        246 => 'decimal',
        10 => 'date',
        12 => 'datetime',
        7 => 'timestamp',
        13 => 'year',
        254 => 'char',
        253 => 'varchar',
        252 => 'tinyblob'
    ];
    public static $KeyTypes = [
        'None' => 0,
        'Primary' => 1,
        'Foreign' => 2
    ];

    public function __construct()
    {
    }

    private function SetFlags($flags){
        if ($flags & 1){
            $this->Nullable = 0;
        }
        if ($flags & 2){
            $this->KeyType = 1;
        }
        if ($flags & 32){
            $this->Unsigned = true;
        }
        if ($flags & 512){
            $this->AutoIncrement = true;
        }
    }

    public static function NewFromDescribe($row, $tableName, SqlTable $foreignTable = null)
    {
        $newType = new SqlType();
        $newType->Name = $row['Field'];
        $newType->Table = $tableName;
        $newType->Type = self::ParseType($row['Type']);
        $newType->Length = self::ParseLength($row['Type']);
        $newType->Unsigned = strpos($row['Type'], 'unsigned') !== false;
        $newType->Nullable = $row['Null'] !== 'NO';
        $newType->KeyType = $row['Key'] !== "";
        $newType->ForeignTable = $foreignTable;
        $newType->AutoIncrement = strpos($row['Extra'], "auto_increment") ? true : false;
        return $newType;
    }

    public static function NewFromFetch($definition){
        $newType = new SqlType();
        $newType->Name = $definition->name;
        $newType->OriginalName = $definition->orgname;
        $newType->Table = $definition->table;
        $newType->Table = $definition->orgtable;
        $newType->Length = $definition->max_length;
        $newType->Type = $definition->type;
        $newType->Decimals = $definition->type;
        $newType->SetFlags($definition->flags);
        return $newType;
    }

    public function Print(){
        print "<ol style='list-style-type:none'>{$this->Name} [";
        print "<ul>Table: $this->Table ,</ul>";
        print "<ul>Type: $this->Type,</ul>";
        print "<ul>Length: $this->Length,</ul>";
        print "<ul>Unsigned: $this->Unsigned,</ul>";
        print "<ul>Nullable: $this->Nullable,</ul>";
        print "<ul>KeyType: $this->KeyType,</ul>";
        print "<ul>Auto-Increment: $this->AutoIncrement </ul>";
        if ($this->ForeignTable != null) print "<li>" . $this->ForeignTable->Print() . ",</li>";
        print "]</ol>";
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

    public function GetValueOfKey(){
        if ($this->ForeignTable == null) return null;
        $query = <<<QUERY
Select * from $this->ForeignTable->Name
where $this->ForeignTable->Members[$this->ForeignTable->PrimaryKey] = $this->Value
QUERY;
        Sql::Query($query ,$this->ForeignTable);
    }
}