<?php

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once $_SERVER['DOCUMENT_ROOT']. "/Sql/SqlType.php";
include_once $_SERVER['DOCUMENT_ROOT']. "/Sql/SqlCollection.php";
include_once $_SERVER['DOCUMENT_ROOT']. "/Sql/SqlObject.php";
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
    public static function GetAllFromTable($table){
        $query = "Select * From $table";
        $model = self::GenerateModel($table, false);
        return self::Query($query, $model);
    }
    private static function GenerateSubModel($tableName, $includeSubTables = true){
        $subModel = new SqlObject($tableName);
        if ($res = self::$_dbConnection->query("DESCRIBE " . self::$_dbName . ".{$tableName}")){
            while ($row = mysqli_fetch_array($res)){
                if ($row['Key'] == "MUL" && $includeSubTables){
                    $foreignTableInfo = self::GetForeignTableInfo(self::$_dbName, $tableName, $row['Field']);
                    if ($foreignTableInfo['Source'] != "") {
                        $subModel->Fields[$row['Field']] = SqlType::NewFromDescribe($row, $tableName ,self::GenerateSubModel($foreignTableInfo['Source']));
                    }
                    else {
                        $subModel->Fields[$row['Field']] = [];
                    }
                }
                else {
                    $subModel->Fields[$row['Field']] = SqlType::NewFromDescribe($row, $tableName);
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

    public static function Query($sql, $model = null){
        self::Connect();
        self::Use(self::$_dbName);
        $result = new SqlCollection();
        try {
            if ($res = self::$_dbConnection->query($sql)) {
                if ($model == null) $model = self::GenerateModelFromResult($res);
                while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                    $object = $model->Clone();
                    foreach ($row as $key => $value) {
                        $object->Fields[$key]->Value = $value;
                    }
                    $result->AddMember($object);
                }
            }
            else{
                throw new Exception(self::$_dbConnection->error);
            }
        }
        catch (Exception $e){
            self::Disconnect();
            Router::ReDirectError(400, $e->getMessage());
        }
        finally
        {
            self::Disconnect();
        }
        return $result;
    }

    public static function NonQuery($sql){
        self::Connect();
        self::Use(self::$_dbName);
        $res = null;
        try{
            $res = self::$_dbConnection->query($sql);
            return $res;
        }
        catch (Exception $e){
            self::Disconnect();
            Router::ReDirectError(400, $e->getMessage());
        }
        finally
        {
            self::Disconnect();
        }
        return $res;
    }

    private static function GenerateModelFromResult($array){
        $model = new SqlObject();
        while ($result = mysqli_fetch_field($array)){
            $newType = SqlType::NewFromFetch($result);
            $model->AddField($newType);
        }
        return $model;
    }

    public static function ParametrizeValue($value){
        if (is_numeric($value)){
           return $value;
        }
        else return '"' . $value . '"';
    }

    public static function GetLinkedValues($table, $where){
        $model = self::GenerateModel($table, true);
        /** @var  $results SqlCollection */
        $query = self::BuildJoinStatement($model, $where);
        $res = self::Query($query);
        $result = "";
        foreach ($res->Members as $member){
            foreach ($member->Fields as $field){
                $result .= $field->Value . ', ';
            }
        }
        $result = substr($result, 0, strlen($result)-2);
        return $result;
    }

    public static function BuildJoinStatement(SqlObject $model, $where = ''){
        $tables = self::GetPrimaryAndForeignKeyPairs($model);
        $selection = self::GetJoinSelection($tables);
        $keys = array_keys($tables);
        $query = 'select '. $selection .' from ' . $keys[0] . ' ';
        for ($x = 1; $x < count($keys); $x++){
            $curKey = $keys[$x];
            $query .= 'Join ' . $curKey . ' On ' . $curKey.'.'.$tables[$curKey]['pk']->Name . " = ". $tables[$curKey]['fk']->TableName.'.'.$tables[$curKey]['fk']->Name. ' ';
        }
        $query .= 'Where ' . $tables[$keys[0]]['pk']->TableName.'.'.$tables[$keys[0]]['pk']->Name.'='. self::ParametrizeValue($where);
        Helper::PrintArray($model->Fields);
        Helper::Print($query);
        return $query;
    }

    private static function GetPrimaryAndForeignKeyPairs(SqlObject $model){
        $tables = [];
        foreach ($model->Fields as $field){
            if($field->KeyType == 2){
                $tbl = array_values($field->ForeignTable->Fields)[0];
                $obj = [$tbl->TableName => ["pk" => $tbl, "fk"=> $field]];
                $tables += $obj;
                $tables += self::GetPrimaryAndForeignKeyPairs($field->ForeignTable);
            }
        }
        return $tables;
    }

    private static function GetJoinSelection($tables){
        $selection = '';
        foreach ($tables as $key=>$value){
            /** @var  $field SqlType */
            foreach ($value['fk']->ForeignTable->Fields as $field ){
                    if ($field->KeyType !== 0) continue;
                    $selection .= $field->TableName.'.'.$field->Name. ', ';
            }
        }
        $selection = substr($selection, 0, strlen($selection)-2);
        return $selection;
    }
}