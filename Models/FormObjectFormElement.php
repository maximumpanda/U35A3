<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/29/2017
 * Time: 2:01 PM
 */
class FormObjectFormElement
{
    public $DefaultValue;
    public $InputType;
    public $Values;
    public $Enabled = true;
    private $_source;

    public static $InputeTypes = [
        0 => 'text',
        1 => 'number',
        2 => 'ComboBox',
        3 => 'date'
    ];

    public function __construct(SqlType $object)
    {
        $this->_source = $object;
        if($object->KeyType = 1) $this->Enabled = false;
        $this->DefaultValue = $object->Value;
    }

    private function ParseInputType(SqlType $object){
        if ($object->KeyType == 2) return 2;
        if (strpos($object->KeyType, 'int') !== false){

        }
        if (strpos($object->KeyType, "char")!== false){

        }
    }

}