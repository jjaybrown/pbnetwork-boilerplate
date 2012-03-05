<?php
namespace App\Classes\Cart;

class Item
{
    public $code;
    protected $_name;
    protected $_price;
    protected $_quantity;
    protected $_inStock = true;

    public function __construct($code, $name, $price){
        $this->code = $code;
        $this->_name = $name;
        $this->_price = $price;
        $this->_quantity = (int)0;
    }

    public function getName(){
        return $this->_name;
    }

    public function setName($name){
        $this->_name = $name;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function setQuantity($num)
    {
        $this->_quantity = (int)$num;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function addQuantity($q)
    {
        $this->_quantity +=$q;
    }

    public function removeQuantity($q)
    {
        if($this->_quantity >0){
            $this->_quantity -=$q;
        }else{
            return false;
        }

        return true;
    }

    public function setInStock($inStock = true)
    {
        $this->_inStock = $inStock;
    }

    public function isInStock()
    {
        return $this->_inStock;
    }
}