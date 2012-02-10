<?php

namespace App\Classes;

require_once 'PHPUnit/Framework/TestCase.php';

class TicketTest extends \PHPUnit_Framework_TestCase
{
    protected $ticket = null;
    
    public function setUp()
    {
        $this->ticket = new Ticket();
        parent::setUp();
    }
    
    public function tearDown()
    {
        $this->ticket = null;
        parent::TearDown();
    }
    
    public function testPriceIsSet()
    {
        $this->assertEquals(0, $this->ticket->getPrice());
         $this->ticket->setPrice(2.99);
        $this->assertEquals(2.99, $this->ticket->getPrice());
    }
    
    public function testSetEventId()
    {
        $this->ticket->setEventId(1);
        $this->assertEquals(1,$this->ticket->getEventId());
    }
}
