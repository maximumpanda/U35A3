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
        $query = 'Select DISTINCT Id From Authentications Where Email LIKE ' . strtoupper(Sql::ParametrizeValue('%'. $_POST['UserName'].'%')) .
            ' And PasswordHash = ' . Sql::ParametrizeValue($_POST['Password']);
        $res = Sql::Query($query);
        if (isset($res->Members[0]))
            if(strpos(strtoupper($_POST['UserName']), '@PANDA.CO') !== false ){
                Session::$Bag['LoggedIn'] = true;
                Session::$Bag['AuthenticationLevel'] = 10;
                Router::Redirect("/Home/Index");
            }
        Router::Redirect("/Login/Index");
    }
}