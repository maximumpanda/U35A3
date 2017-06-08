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
        if (isset($res->Members[0])) {
            if (strpos(strtoupper($_POST['UserName']), '@PANDA.CO') !== false) {
                Session::$Bag['AuthenticationLevel'] = 10;
            }
            else{
                Session::$Bag['AuthenticationLevel'] =0;
            }
            Session::$Bag['LoggedIn'] = true;
            Router::Redirect("/Home/Index");
        }
        Router::Redirect("/Login/Index");
    }

    public static function PostLogout(){
        Session::$Bag['LoggedIn'] = false;
        Session::$Bag['AuthenticationLevel'] = 0;
        Router::Redirect("/Home/Index");
    }

    public static function GetCreate(){
        $authModel = Sql::GenerateModel("Authentications");
        $clientModel = Sql::GenerateModel("Clients");

        unset($authModel->Fields['Id']);
        unset($clientModel->Fields['Id']);
        Helper::PrintArray($authModel);
        Helper::PrintArray($clientModel);
        $model = $authModel + $clientModel;
        Helper::PrintArray($model);
        return new form();
    }
}