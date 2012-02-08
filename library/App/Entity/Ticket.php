<?php
namespace App\Entity;

/**
 *@Entity(repositoryClass="App\Repository\Ticket")
 * @Table(name="Ticket")
 */

class Ticket
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="integer", name="id") */
    private $_event_id;
    /** @Column(type="float", name="price") */
    private $_price = 0;

    private $_event;

    public function __construct()
    {
        
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price = 0)
    {
        $this->_price = $price;
        return $this;
    }

    public function getEvent()
    {
        return $this->_event;
    }

    public function setEvent(Event $event)
    {
        if($event instanceof Event)
        {
            // Set foriegn key (event_id)
            $this->_event_id = $event->getId();
            $this->_event = $event;
            return true;
        }

        return false;
    }

}