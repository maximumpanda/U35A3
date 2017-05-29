<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 12:31 PM
 */
class SqlType
{
    public $Alias;
    public $Name;
    public $TableAlias;
    public $TableName;
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
        1 => 'tinyint',
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
        else $this->KeyType = 0;
        if ($flags & 32){
            $this->Unsigned = true;
        }
        if ($flags & 512){
            $this->AutoIncrement = true;
        }
    }

    public static function NewFromDescribe($row, $tableName, SqlObject $foreignTable = null)
    {
        $newType = new SqlType();
        $newType->Alias = $row['Field'];
        $newType->Name = $newType->Alias;
        $newType->TableAlias = $tableName;
        $newType->TableName = $newType->TableAlias;
        $newType->Type = self::ParseType($row['Type']);
        $newType->Length = self::ParseLength($row['Type']);
        $newType->Unsigned = strpos($row['Type'], 'unsigned') !== false ? 1 : 0;
        $newType->Nullable = $row['Null'] !== 'NO' ? 1 : 0;
        $newType->KeyType = self::ParseKeyType($row['Key']);
        $newType->ForeignTable = $foreignTable;
        $newType->AutoIncrement = strpos($row['Extra'], "auto_increment") !== false ? 1 : 0;
        return $newType;
    }

    public static function NewFromFetch($definition){
        $newType = new SqlType();
        $newType->Alias = $definition->name;
        $newType->Name = $definition->orgname;
        $newType->TableName = $definition->table;
        $newType->TableAlias = $definition->orgtable;
        $newType->Length = $definition->max_length;
        $newType->Type = SqlType::$TypeCodes[$definition->type];
        $newType->Decimals = $definition->type;
        $newType->SetFlags($definition->flags);
        return $newType;
    }

    public function Print(){
        print "<ol style='list-style-type:none'>$this->Alias [";
        print "<ul>original name: $this->Name,</ul>";
        print "<ul>Table: $this->TableAlias ,</ul>";
        print "<ul>Type: $this->Type,</ul>";
        print "<ul>Length: $this->Length,</ul>";
        print "<ul>Unsigned: $this->Unsigned,</ul>";
        print "<ul>Nullable: $this->Nullable,</ul>";
        print "<ul>KeyType: $this->KeyType,</ul>";
        print "<ul>Auto-Increment: $this->AutoIncrement,</ul>";
        if ($this->Value != null) print "<ul>Value: $this->Value,</ul>";
        if ($this->ForeignTable != null) print "<li>" . $this->ForeignTable->Print() . ",</li>";
        print "]</ol>";
    }

    private static function ParseType($raw){
        Helper::Print($raw);
        $end = strpos($raw, "(");
        if ($end == false) return $raw;
        return substr($raw, 0, $end);
    }

    private static function ParseLength($raw){
        $start = strpos($raw, "(")+1;
        $end = strpos($raw, ")");
        return substr($raw, $start,$end-$start);
    }

    private static function ParseKeyType($key){
        if ($key == 'PRI') return 1;
        if ($key == 'MUL') return 2;
        return 0;
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