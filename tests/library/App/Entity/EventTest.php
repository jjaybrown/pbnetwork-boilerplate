<?php

namespace App\Entity;

require_once 'PHPUnit/Framework/TestCase.php';

class EventTest extends \PHPUnit_Framework_TestCase 
{
    
    public function setUp()
    {
        $this->event = new Event("test","2012-02-01 12:45:00","2012-02-01 12:45:00","test location",0);
        parent::setUp();
    }
    
    public function tearDown()
    {
        $this->ticket = null;
        parent::TearDown();
    }
    
    public function testCreatedReturnsValidDate()
    {
        
        $this->event->setCreated(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $this->event->getCreated()->format("Y-m-d H:i:s"));
    }
    
    public function testStartDateReturnsValidDate()
    {
        $this->event->setStartDate(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $this->event->getStartDate()->format("Y-m-d H:i:s"));
    }
    
    public function testEndDateReturnsValidDate()
    {
        $this->event->setEndDate(new \DateTime("2012-02-01 12:45:00"));
        $this->assertEquals("2012-02-01 12:45:00", $this->event->getEndDate()->format("Y-m-d H:i:s"));
    }
    
    public function testSetLocation()
    {
        $this->event->setLocation("test location");
        $this->assertEquals("test location", $this->event->getLocation());
    }
    
    public function testNumTicketsZeroByDefault()
    {
        $this->assertEquals(0, $this->event->getNumTickets());
    }
    
    public function testAddingTickets()
    {
        $this->event->addTickets(3);
        $this->assertEquals(3,$this->event->getNumTickets());
    }
    
    public function testAddingTicketsThenRemovingTickets()
    {
        $this->event->addTickets(3);
        $this->event->removeTickets(1);
        $this->assertEquals(2,$this->event->getNumTickets());
    }
    
    public function testNumberTicketsCantGoBelowZero()
    {
        $this->event->addTickets(3);
        $this->event->removeTickets(4);
        $this->assertEquals(3,$this->event->getNumTickets());
    }
    
    public function testNumberTicketsCantGoBelowZeroAndReturnsFalse()
    {
        $this->event->addTickets(3);
        $this->assertFalse($this->event->removeTickets(4));
    }

    public function testIsEventFree()
    {
        $this->assertTrue($this->event->isFree());
    }

    public function testIsEventNotFree()
    {
        $this->event->setFree(false);
        $this->assertFalse($this->event->isFree());
    }

    public function testIsEventNotFreeAndHasAPriceSet()
    {
        $this->event->setPrice(2.99);
        $this->assertFalse($this->event->isFree());
        $this->assertEquals(2.99,$this->event->getPrice());
    }
}
