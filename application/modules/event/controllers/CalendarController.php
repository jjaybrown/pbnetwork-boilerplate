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
    /**
     *
     */
    public function init()
    {
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
        $calendarService = new \App\Service\Calendar("$month $year");
        $calendarService->setValidDates(0, 0); //11 months back, 4 months forward

        // Build the calendar header data
        $this->view->monthHeader = $calendarService->getFocusDate()->get("MMMM yyyy");
        $this->view->calHeader = $calendarService->getCalendarHeaderDataArray(
            'calendar', //controller
            'view' //action
        );

        // Build the calendar weekdays data
        $this->view->calWeekdays = $calendarService->getCalendarWeekdayDataArray();

        // Build the calendar monthdays data
        $this->view->calMonthDays = $calendarService->getCalendarMonthDayDataArray();

        // Render the calendar view script
        //$this->view->render('view-calendar.php');
    }
}
