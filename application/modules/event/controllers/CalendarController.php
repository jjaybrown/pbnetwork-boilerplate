<?php
/**
 * Calendar Controller
 *
 * @author Arietis Software
 * @copyright 2011 Arietis Software
 * @license http://www.arietis-software.com/license/gnu/license.txt
 *
 */
class Event_CalendarController extends Zend_Controller_Action
{
    
    private $_calendarService = null;
    
    public function init()
    {
        $this->_calendarService = new \App\Service\Calendar();
        $this->_calendarService->setValidDates(0, 11); //11 months back, 4 months forward
    }
    
    public function indexAction()
    {
        // Build the calendar header data
        $this->view->monthHeader = $this->_calendarService->getFocusDate()->get("MMMM yyyy");
        $this->view->calHeader = $this->_calendarService->getCalendarHeaderDataArray();
        
         // Build the calendar weekdays data
        $this->view->calWeekdays = $this->_calendarService->getCalendarWeekdayDataArray();

        // Build the calendar monthdays data
        $this->view->calMonthDays = $this->_calendarService->getCalendarMonthDayDataArray();
    }
    
    /**
     *
     */
    public function viewAction()
    {
        // Request params
        $request = $this->getRequest();
        $month = $request->getParam('month', date('F'));
        $year = $request->getParam('year', date('Y'));

        // CalendarService
        $this->_calendarService->setFocusDate("$month $year");
        
        // Build the calendar header data
        $this->view->monthHeader = $this->_calendarService->getFocusDate()->get("MMMM yyyy");
        $this->view->calHeader = $this->_calendarService->getCalendarHeaderDataArray();
        
         // Build the calendar weekdays data
        $this->view->calWeekdays = $this->_calendarService->getCalendarWeekdayDataArray();

        // Build the calendar monthdays data
        $this->view->calMonthDays = $this->_calendarService->getCalendarMonthDayDataArray();
    }
}
