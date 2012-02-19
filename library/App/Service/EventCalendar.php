<?php

/**
 * Service_Calendar (using Zend_Date)
 *
 * @author Arietis Software
 * @copyright 2011 Arietis Software
 * @license http://www.arietis-software.com/license/gnu/license.txt
 *
 */
namespace App\Service;

class EventCalendar extends Calendar
{
    protected $_events;

    public function getEvents(){
        return $this->_events;
    }

    public function setEvents(array $events){
        $this->_events = $events;
    }

    /**
     * @return Array $calMonthDays
     */
    public function getCalendarMonthDayDataArray(){
        $today = $this->getNow()->get("d");
        $nowDate = $this->getNow()->get("MMM yyyy");
        $focusDate = $this->getFocusDate()->get("MMM yyyy");

        $calDayNum = 1; //first day
        $calMonthDays = array();
        for ($i = 0; $i < $this->getFocusMonthNumWeeks(); $i++) {
            $weekArr = array();
            for ($j = 0; $j < 7; $j++) {
                // Create new Day object
                $day = new \App\Classes\Calendar\EventDay();
                // Set Day's month
                $day->setMonth($this->getFocusDate()->get("M"));
                // Set Day's year
                $day->setYear($this->getFocusDate()->get("yyyy"));
                $cellNum = ($i * 7 + $j);
                //css class cals
                if (($nowDate == $focusDate) && ($today == $calDayNum) && ($cellNum >= $this->getFocusMonthFirstDayOfWeek())) { //today
                    $day->setClass("today");
                    // Set Day as today
                    $day->setToday();
                }
                if ($j == 0) { //first day of week
                    $day->setClass($day->getClass()." first");
                } elseif ($j == 6) { //last day of week
                    $day->setClass($day->getClass()." last");
                }
                if ($i == ($this->getFocusMonthNumWeeks() - 1)) { //last week of days
                    $day->setClass($day->getClass()." bottom");
                }

                //build the days of the month cell data
                $firstDayOfWeek = $this->getFocusMonthFirstDayOfWeek();
                if ($cellNum >= $firstDayOfWeek && $cellNum < ($this->getFocusMonthNumDays() + $firstDayOfWeek)) { //day in cell
                    $day->setNum(\Zend_Locale_Format::toNumber($calDayNum));
                    $calDayNum++;
                }

                // Get any events for this day
                foreach($this->_events as $event){
                    if($event->getStartDate('j') == $day->getNum()){
                        $day->addEvent($event);
                    }
                }
                array_push($weekArr, $day);
            }
            array_push($calMonthDays, $weekArr);
        }
        return $calMonthDays;
    }
}
