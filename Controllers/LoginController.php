<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 6/5/2017
 * Time: 9:40 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
class LoginController implements IController
{

    public static function AuthenticationLevel()
    {
        return 0;
    }

    public static function GetIndex()
    {
    }
    public static function PostIndex(){
        $query = 'Select DISTINCT Id From Authentications Where Email LIKE ' . strtoupper(Sql::ParametrizeValue('%'. $_POST['UserName'].'%'));
        $res = Sql::Query($query);
        Helper::PrintArray($query);
        Helper::PrintArray($res);
        Helper::PrintArray($_POST);
        exit();
    }
}