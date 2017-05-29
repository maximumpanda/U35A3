<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/29/2017
 * Time: 2:01 PM
 */
class FormElement
{
    public $Name;
    public $DefaultValue;
    public $InputType;
    public $Values;
    public $MaxLength;
    public $Disabled = false;
    /** @var SqlType $_source */
    private $_source;

    public static $InputTypes = [
        0 => 'text',
        1 => 'number',
        2 => 'select',
        3 => 'date',
        4 => 'checkbox'
    ];

    public function __construct(SqlType $object)
    {

        $this->Name = $object->Name;
        $this->_source = $object;
        $this->MaxLength = $object->Length;
        if($object->KeyType == 1) $this->Disabled = true;
        $this->DefaultValue = $object->Value != null ? $object->Value : "";
        $this->InputType = FormElement::$InputTypes[$this->ParseInputType($object)];
    }

    public function BuildHtml(){
        Helper::PrintArray($this);
        $html = $this->Name . ': ' .
            '<input type="' . $this->InputType .
            ' name="' . $this->Name .
            ' maxlength="' . $this->MaxLength .
            ' value="' . $this->DefaultValue .'"' .
            ' disabled="' . $this->Disabled . '"><br>';
        return $html;
    }

    private function ParseInputType(SqlType $object){
        if ($object->KeyType == 2) return 2;
        if (strpos($object->Type, 'int') !== false){
            if ($object->Type == 'tinyint') {
                if ($object->Length == 1) return 4;
            }
            return 1;
        }
        if (strpos($object->Type, 'char')!== false){
            return 0;
        }
        if (strpos($object->Type, 'date') !== false){
            return 3;
        }
        return 0;
    }

}