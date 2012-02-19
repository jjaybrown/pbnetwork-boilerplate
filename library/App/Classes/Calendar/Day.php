<?php
namespace App\Classes\Calendar;

class Day
{
    protected $_num;
    protected $_month;
    protected $_year;
    protected $_today = false;

    public function __construct($num = null,$month = null,$year = null){
        $this->_num = $num;
        $this->_month = $month;
        $this->_year = $year;
    }

    public function getNum(){
        return $this->_num;
    }

    public function setNum($num){
        $this->_num = $num;
    }

    public function getMonth(){
        return $this->_month;
    }

    public function setMonth($month){
        $this->_month = $month;
    }

    public function getyear(){
        return $this->_year;
    }

    public function setYear($year){
        $this->_year = $year;
    }

    public function setToday($isToday = true){
        $this->_today = $isToday;
    }

    public function isToday(){
        return $this->_today;
    }
}