<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once "SqlType.php";
include_once "SqlTable.php";
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

    private static function GenerateSubModel($name, $includeSubTables = true){
        $subModel = new SqlTable($name);
        if ($res = self::$_dbConnection->query("DESCRIBE " . self::$_dbName . ".{$name}")){
            while ($row = mysqli_fetch_array($res)){
                if ($row['Key'] == "MUL" && $includeSubTables){
                    $foreignTableInfo = self::GetForeignTableInfo($name, $row['Field']);
                    if ($foreignTableInfo['Source'] != "") {
                        $subModel->Members[$row['Field']] = new SqlType($row, $name ,self::GenerateSubModel($foreignTableInfo['Source']));
                    }
                    else {
                        $subModel->Members[$row['Field']] = [];
                    }
                }
                else {
                    if ($row['Key'] == "PRI") {
                        $subModel->PrimaryKey = $row['Field'];
                    }
                    $subModel->Members[$row['Field']] = new SqlType($row, $name);
                }
            }
        }
        return $subModel;
    }

    private static function GetForeignTableInfo($table, $field){
        $query =
            "select referenced_table_name as Source,referenced_column_name ".
            "from information_schema.key_column_usage ".
            "where referenced_table_name is not null ".
            "and table_schema = '". self::$_dbName . "' ".
            "and table_name = '{$table}' ".
            "and column_name = '{$field}';";
        //print $query;
        if ($res = self::$_dbConnection->query($query)){
            return $res->fetch_array(MYSQLI_ASSOC);
        }
    }

    public static function Query($sql){
        self::Connect();
        self::Use(self::$_dbName);
        $result = [];
        if ($res = self::$_dbConnection->query($sql)){
            while($row = $res->fetch_array(MYSQLI_ASSOC)){
                Helper::PrintArray($row);
            }
        }
        self::Disconnect();
        return $result;
    }
    
    public static function ParseRowToModel($row, $model){

    }
}