<?php

namespace App\Entity;

require_once 'PHPUnit/Framework/TestCase.php';

class EventTest extends \PHPUnit_Framework_TestCase {

    public function testCreatedDateIsDateTimeObject()
    {
        $Event = new Event();
        $this->assertTrue();
    }

}
