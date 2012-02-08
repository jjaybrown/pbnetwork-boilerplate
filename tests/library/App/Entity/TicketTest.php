<?php

namespace App\Entity;

require_once 'PHPUnit/Framework/TestCase.php';

class TicketTest extends \PHPUnit_Framework_TestCase {

    public function testPriceIsSet()
    {
        $ticket = new Ticket();
        $this->assertEquals(0,$ticket->getPrice());
        $ticket->setPrice(2.99);
        $this->assertEquals(2.99,$ticket->getPrice());
    }

    public function testSettingEventToTicket()
    {
        $event = new Event("test","2012-02-01 12:45:00","2012-02-01 12:45:00","test location",15);
        $ticket = new Ticket();
        $this->assertTrue($ticket->setEvent($event));
    }
}
