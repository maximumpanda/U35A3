<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:54 PM
 */

namespace Api;


class AController
{
    public static function FourOhFour(){
        return http_response_code(404);
    }
}