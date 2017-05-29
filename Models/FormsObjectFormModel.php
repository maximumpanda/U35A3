<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/29/2017
 * Time: 1:54 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Models/FormObjectFormElement.php";
class FormsObjectFormModel
{
    /** @var FormObjectFormElement[] $Fields */
    public $Fields = [];
    /** @var  SqlObject $Model */
    public $Model;
    public $Status = "Success";
    public $StatusMessage = "";

}