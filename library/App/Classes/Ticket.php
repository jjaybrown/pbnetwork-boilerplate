<?php
namespace App\Classes;

class Ticket extends Item
{
    protected $_eventId;
    protected $_eventName;
    protected $_price = 0;

    public function __construct($eventId,$eventName , $price){
        $this->_eventId = $eventId;
        $this->_eventName = $eventName;
        $this->_price = $price;
        parent::__construct($this->_eventId, $this->_eventName, $this->_price);
    }

    public function getEventId(){
        return $this->_eventId;
    }

    public function setEventId($id){
        $this->_eventId = $id;
        return $this;
    }

    public function getEventName(){
        $this->_eventName;
    }
    
    public function setEventName($eventName){
        $this->_eventName = $eventName;
        parent::setName($this->_eventName);
    }

    public function getPrice(){
        return $this->_price;
    }

    public function setPrice($price = 0){
        $this->_price = $price;
        parent::setPrice($this->_price);
        return $this;
    }
}