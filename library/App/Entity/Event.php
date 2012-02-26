<?php
namespace App\Entity;

/**
 *@Entity(repositoryClass="App\Repository\Event")
 * @Table(name="event") 
 */

class Event
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue 
     */
    private $_id;
    /** @Column(type="datetime", name="created")*/
    private $_createdDate;
     /** @Column(type="datetime", name ="start")*/
    private $_startDate;
     /** @Column(type="datetime", name="end")*/
    private $_endDate;
    /** @Column(type="string", name="name", length=160) */
    private $_name;
    /** @Column(type="string", name="location", length=255, nullable=true) */
    private $_location;
    /** @Column(type="string", name="venue", length=255, nullable=true) */
    private $_venue;
    /** @Column(type="string", name="description", length=500, nullable=true) */
    private $_description;
    /** @Column(type="integer", name="num_tickets") */
    private $_numTickets = 0;
    /** @Column(type="boolean", name="free") */
    private $_free = true;
    /** @Column(type="float", name="price") */
    private $_price = 0;

    public function __construct($name, $start, $end, $location, $tickets)
    {
        $this->setCreated();
        $this->setName($name);
        $this->setStartDate(new \DateTime($start));
        $this->setEndDate(new \DateTime($end));
        $this->setLocation($location);
        $this->setNumTickets($tickets);
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getCreated()
    {
        return $this->_createdDate;
    }
    
    public function setCreated(\DateTime $date = null)
    {
        if(!is_null($date)){
            $this->_createdDate = $date;
        }else{
            $date = new \DateTime();
            $this->_createdDate = $date;
        }
        return $this;
    }
    
    public function getStartDate($format = 'Y-m-d H:i:s')
    {
        return $this->_startDate->format($format);
    }

    public function setStartDate(\DateTime $date)
    {
        $this->_startDate= $date;
    }
    
    public function getEndDate($format = 'Y-m-d H:i:s')
    {
        return $this->_endDate->format($format);
    }
    
    public function setEndDate(\DateTime $date)
    {
        $this->_endDate= $date;
    }
    
    public function getName()
    {
        return ucfirst(stripslashes($this->_name));
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getVenue()
    {
        return $this->_venue;
    }

    public function setVenue($venue)
    {
        $this->_venue = $venue;
        return $this;
    }

    public function getLocation()
    {
        return $this->_location;
    }
    
    public function setLocation($location)
    {
        $this->_location = $location;
        return $this;
    }
    
    public function getDescription()
    {
        return stripslashes($this->_description);
    }
    
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }
    
    public function getNumTickets()
    {
        return $this->_numTickets;
    }
    
    public function setNumTickets($tickets)
    {
        $this->_numTickets = $tickets;
        return $this;
    }
    
    public function addTickets($num)
    {
        $available = $this->_numTickets;
        $available += $num;
        $this->_numTickets = $available;
    }
    
    public function removeTickets($num)
    {
        $available = $this->_numTickets;
        if($available - $num >= 0)
        {
            $available -= $num;
            $this->_numTickets = $available;
            return true;
        }
        
        return false;
    }

    public function isFree()
    {
        return $this->_free;
    }

    public function setFree($isFree)
    {
        $this->_free = $isFree;
        return $this;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price = 0)
    {
        if($price != 0){
            $this->_price = $price;
            $this->setFree(false);
        }
        return $this;
    }

    public function isSoldOut()
    {
        if($this->_numTickets == 0){
            return true;
        }

        return false;
    }
    
    
}