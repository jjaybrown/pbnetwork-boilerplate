<?php

namespace App\Entity;

require_once 'PHPUnit/Framework/TestCase.php';

class EventTest extends \PHPUnit_Framework_TestCase {

    public function testCreatedDateIsDateTimeObject()
    {
        
    }
    
    public function testCreatedReturnsValidDate()
    {
        $event = new Event();
        $event->setCreated(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $event->getCreated()->format("Y-m-d H:i:s"));
    }
    
    public function testStartDateReturnsValidDate()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $event->getStartDate()->format("Y-m-d H:i:s"));
    }
    
    public function testEndDateReturnsValidDate()
    {
        $event = new Event();
        $event->setEndDate(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $event->getEndDate()->format("Y-m-d H:i:s"));
    }
    
    public function testSetLocation()
    {
        $event = new Event();
        $event->setLocation("test location");
        $this->assertEquals("test location", $event->getLocation());
    }
    
    public function testNumTicketsZeroByDefault()
    {
        $event = new Event();
        $this->assertEquals(0, $event->getNumTickets());
    }
    
    public function testAddingTickets()
    {
        $event = new Event();
        $event->addTickets(3);
        $this->assertEquals(3,$event->getNumTickets());
    }
    
    public function testAddingTicketsThenRemovingTickets()
    {
        $event = new Event();
        $event->addTickets(3);
        $event->removeTickets(1);
        $this->assertEquals(2,$event->getNumTickets());
    }
    
    public function testNumberTicketsCantGoBelowZero()
    {
        $event = new Event();
        $event->addTickets(3);
        $event->removeTickets(4);
        $this->assertEquals(3,$event->getNumTickets());
    }
    
    public function testNumberTicketsCantGoBelowZeroAndReturnsFalse()
    {
        $event = new Event();
        $event->addTickets(3);
        $this->assertFalse($event->removeTickets(4));
    }
}
