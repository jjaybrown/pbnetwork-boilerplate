<?php
namespace App\Entity\Common;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Common\Manufacturer")
 * @Table(name="manufacturers")
 */
class Manufacturer
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /* @Column(type="string", name="name") */
    private $_name;
    /* @Column(type="string", name="address1") */
    private $_address1;
    /* @Column(type="string", name="address2", nullable="true") */
    private $_address2;
    /* @Column(type="string", name="city_town") */
    private $_cityTown;
    /* @Column(type="string", name="county") */
    private $_county;
    /* @Column(type="string", name="postcode") */
    private $_postcode;
    /* @Column(type="string", name="country") */
    private $_country;
    
    public function __construct($name, $address1, $address2, $cityTown, $county, $postcode, $country)
    {
        $this->_name = $name;
        $this->_address1 = $address1;
        $this->_address2 = $address2;
        $this->_cityTown = $cityTown;
        $this->_county = $county;
        $this->_postcode = $postcode;
        $this->_country = $country; 
    }
    
    public function getId()
    {
        return $this->_id;
    }
}