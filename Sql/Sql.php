<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once "SqlType.php";
include_once "SqlTable.php";
include_once "SqlCollection.php";
include_once "SqlObject.php";
class Sql
{
    private static $_dbConnection;
    private static $_dbName = "u35a1";

    public static function UseDatabase($name){
        self::$_dbName = $name;
    }

    private static function Connect(){
        self::$_dbConnection = new mysqli('localhost', 'root', 'TestEnvironmentPassword');
    }

    public static function Disconnect(){
        self::$_dbConnection->close();
    }

    public static function Use($name){
        self::$_dbConnection->select_db($name);
    }
    public static function GenerateModel($name, $includeSubTables = true){
        self::Connect();
        self::Use("information_schema");
        $model = self::GenerateSubModel($name, $includeSubTables);
        self::Disconnect();
        return $model;
    }
    public static function GetTables($database){
        $query = "Select table_name as name from Information_schema.tables Where table_type = 'base table'and table_schema = '$database'";
        return self::Query($query);
    }

    private static function GenerateSubModel($name, $includeSubTables = true){
        $subModel = new SqlTable($name);
        if ($res = self::$_dbConnection->query("DESCRIBE " . self::$_dbName . ".{$name}")){
            while ($row = mysqli_fetch_array($res)){
                if ($row['Key'] == "MUL" && $includeSubTables){
                    $foreignTableInfo = self::GetForeignTableInfo(self::$_dbName, $name, $row['Field']);
                    if ($foreignTableInfo['Source'] != "") {
                        $subModel->Members[$row['Field']] = SqlType::NewFromDescribe($row, $name ,self::GenerateSubModel($foreignTableInfo['Source']));
                    }
                    else {
                        $subModel->Members[$row['Field']] = [];
                    }
                }
                else {
                    if ($row['Key'] == "PRI") {
                        $subModel->PrimaryKey = SqlType::$KeyTypes['Primary'];
                    }
                    $subModel->Members[$row['Field']] = SqlType::NewFromDescribe($row, $name);
                }
            }
        }
        return $subModel;
    }

    private static function GetForeignTableInfo($db, $table, $field){
        $query = <<<"QUERY"
select referenced_table_name as Source,referenced_column_name
from information_schema.key_column_usage
where referenced_table_name is not null
and table_schema = '{$db}'
and table_name = '{$table}'
and column_name = '{$field}';
QUERY;
        //print $query;
        if ($res = self::$_dbConnection->query($query)){
            return $res->fetch_array(MYSQLI_ASSOC);
        }
    }

    public static function Query($sql){
        self::Connect();
        self::Use(self::$_dbName);
        $result = new SqlCollection();
        if ($res = self::$_dbConnection->query($sql)){
            $val = mysqli_num_rows($res);
            $model = self::GenerateModelFromResult($res);
            while($row = $res->fetch_array(MYSQLI_ASSOC)){
                $object = clone $model;
                Helper::Print($object->Fields['name']->Value);
                foreach ($row as $key => $value){
                    $object->Fields[$key]->Value = $value;
                    Helper::Print("$key :: $value");
                }
                Helper::Print("Added");
                $result->AddMember($object);
            }
        }
        self::Disconnect();
        return $result;
    }

    private static function GenerateModelFromResult($array){
        $model = new SqlObject();
        while ($result = mysqli_fetch_field($array)){
            $newType = SqlType::NewFromFetch($result);
            $model->AddField($newType);
        }
        return $model;
    }
}