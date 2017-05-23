<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 5/9/2017
 * Time: 11:51 AM
 */

include_once 'Sql.php';

$model = Sql::GenerateModel("Clients", true);
$model->Print();
