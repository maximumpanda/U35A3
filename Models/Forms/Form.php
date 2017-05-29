<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/29/2017
 * Time: 1:54 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . '/Models/Forms/FormElement.php';
class Form{
    /** @var FormElement[] $Fields */
    public $Fields = [];
    /** @var  SqlObject $Model */
    public $Model;
    public $Status = "Success";
    public $StatusMessage = "";

    public static function NewFromModel(SqlObject $model){
        $form = new Form();
        foreach ($model->Fields as $field){
            $form->AddElement($field);
        }
        $form->Model = $model;
        return $form;
    }

    public function AddElement(SqlType $modelField){
        array_push($this->Fields, new FormElement($modelField));
    }

    public function Build(){
        $html = "";
        foreach ($this->Fields as $field){
            $html = $html . $field->BuildHtml();
        }
    }
}