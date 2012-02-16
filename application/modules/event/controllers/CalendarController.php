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
    private $_events = null;
    
     /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;
    
    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_calendarService = new \App\Service\Calendar();
        $this->_calendarService->setValidDates(0, 11); //11 months back, 4 months forward
    }
    
    public function indexAction()
    {
        // Build the calendar header data
        $this->view->monthHeader = $this->_calendarService->getFocusDate()->get("MMMM yyyy");
        $this->view->previousMonth = array(
            'controller' => 'calendar',
            'action' => 'view',
            'month' => $this->_calendarService->getPrevMonth()->get("M"),
            'year' => $this->_calendarService->getPrevMonth()->get("yyyy")
        );
        
        $this->view->nextMonth = array(
            'controller' => 'calendar',
            'action' => 'view',
            'month' => $this->_calendarService->getNextMonth()->get("M"),
            'year' => $this->_calendarService->getNextMonth()->get("yyyy")
        );
        
        //$this->view->calHeader = $this->_calendarService->getCalendarHeaderDataArray();
        
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

        // Set focus date on calendar service
        $this->_calendarService->setFocusDate("$month $year");
        
        // Build the calendar header data
        $this->view->monthHeader = $this->_calendarService->getFocusDate()->get("MMMM yyyy");
        $this->view->previousMonth = array(
            'controller' => 'calendar',
            'action' => 'view',
            'month' => $this->_calendarService->getPrevMonth()->get("M"),
            'year' => $this->_calendarService->getPrevMonth()->get("yyyy")
        );
        
        $this->view->nextMonth = array(
            'controller' => 'calendar',
            'action' => 'view',
            'month' => $this->_calendarService->getNextMonth()->get("M"),
            'year' => $this->_calendarService->getNextMonth()->get("yyyy")
        );
        
        //$this->view->calHeader = $this->_calendarService->getCalendarHeaderDataArray();
        
         // Build the calendar weekdays data
        $this->view->calWeekdays = $this->_calendarService->getCalendarWeekdayDataArray();

        // Build the calendar monthdays data
        $this->view->calMonthDays = $this->_calendarService->getCalendarMonthDayDataArray();
        $this->_getEventsForFocusedMonth($this->_calendarService->getFocusDate());
    }
    
    protected function _getEventsForFocusedMonth(\Zend_Date $focus = null)
    {
        // Check if a focused month has been provided
        if(is_null($focus)){
            $focus = $this->_calendarService->getFocusDate();
        }
        
        $data = $this->_em->getRepository("\App\Entity\Event")->findByEventStartDate($focus);
        Zend_Debug::dump($data);
    }
}
