<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:54 PM
 */

class AlphaController implements IController
{
    public static function GetIndex(){
        return http_response_code(404);
    }

    public static function AuthenticationLevel()
    {
        return 1;
    }
}