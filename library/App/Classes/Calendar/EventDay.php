<?php
namespace App\Classes\Calendar;

class EventDay extends Day
{
    protected $_events = array();
    protected $_class = "";

    public function  __construct($num = null, $month = null, $year = null) {
        parent::__construct($num, $month, $year);
    }

    public function addEvent(\App\Entity\Event $event){
        // Check if we've been given an instance of Event
        if($event instanceof \App\Entity\Event){
            array_push($this->_events, $event);
        }
    }

    public function removeEvent($key){
        // Check the given key exists
        if(array_key_exists($key, $this->_events)){
            unset($this->_events[$key]);
            // Reset array keys after deletion
            array_values($this->_events);
        }
    }

    public function getEvents(){
        return $this->_events;
    }

    public function hasEvents(){
        if(count($this->_events) > 0){
            return true;
        }

        return false;
    }

    public function getClass(){
        return$this->_class;
    }

    public function setClass($className){
        $this->_class = $className;
    }
}