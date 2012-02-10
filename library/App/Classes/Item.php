<?php
namespace App\Classes;

class Item
{
    protected $id;
    protected $name;

    public function __construct($name){
        $this->name = $name;
    }

    public function getName(){
        return $thus->name;
    }
}

