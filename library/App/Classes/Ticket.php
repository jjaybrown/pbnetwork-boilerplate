<?php
namespace App\Classes;

class Ticket
{
    private $_eventId;
    private $_price = 0;

    public function __construct($eventId, $price)
    {
        $this->_eventId = $eventId;
        $this->_price = $price;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price = 0)
    {
        $this->_price = $price;
        return $this;
    }
    
    public function getEventId()
    {
        return $this->_eventId;
    }
    
    public function setEventId($id)
    {
        $this->_eventId = $id;
        return $this;
    }

}