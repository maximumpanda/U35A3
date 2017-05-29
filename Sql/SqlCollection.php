<?php

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 5/23/2017
 * Time: 8:28 PM
 */
class SqlCollection
{
    public $Members = [];

    public function AddMember(SqlObject $newMember){
        array_push($this->Members, $newMember);
    }

    public function Print(){
        foreach ($this->Members as $member){
            $member->Print();
        }
    }

}