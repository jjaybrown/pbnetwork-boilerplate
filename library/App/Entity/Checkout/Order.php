<?php
namespace App\Entity\Checkout;


/**
 *@Entity(repositoryClass="App\Repository\Checkout\Order")
 * @Table(name="orders")
 */

class Order
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue 
     */
    private $_id;
    
    /** @Column(type="string", name="order_status", length="50") */
    private $_status = "";
    /** @Column(type="string", name="payment_type", length="50") */
    private $_paymentType = "";
    /** @Column(type="string", name="first", length="50", nullable = "true") */
    private $_firstName = "";
    /** @Column(type="string", name="last", length="50", nullable = "true") */
    private $_lastName = "";
    /** @Column(type="string", name="street", length="150", nullable = "true") */
    private $_street = "";
    /** @Column(type="string", name="city", length="50", nullable = "true") */
    private $_city = "";
    /** @Column(type="string", name="county", length="50", nullable = "true") */
    private $_county = "";
    /** @Column(type="string", name="country", length="50", nullable = "true") */
    private $_country = "";
    /** @Column(type="string", name="post_code", length="50", nullable = "true") */
    private $_postCode = "";
    /** @Column(type="text", name="items", length="500") */
    private $_items = array();
    /**
     * Constructor
     */
    public function __construct($status, $items = array()){
       $this->_status = $status;
       //$this->_orderDate = \DateTime();
       /*$this->_paymentType = $paymentType;
       $this->_firstName = $firstName;
       $this->_lastName = $lastName;
       $this->_street = $street;
       $this->_city = $city;
       $this->_county = $county;
       $this->_country = $country;
       $this->_postCode = $postcode;*/
       $this->_items = serialize($items);
    }

    public function getPaymentType()
    {

    }

    public function setPaymentType($type)
    {
        $this->_paymentType = $type;
        return $this;
    }

    public function setFirstName($first)
    {
        $this->_firstName = $first;
        return $this;
    }

    public function setLastName($last)
    {
        $this->_lastName = $last;
        return $this;
    }

    public function setStreet($street)
    {
        $this->_street = $street;
        return $this;
    }

    public function setCity($city)
    {
        $this->_city = $city;
        return $this;
    }

    public function setCounty($county)
    {
        $this->_county = $county;
        return $this;
    }

    public function setCountry($country)
    {
        $this->_country = $country;
        return $this;
    }

    public function setPostCode($postcode)
    {
        $this->_postCode = $postcode;
        return $this;
    }

    public function save()
    {
        $em = \Zend_Registry::get('em');
        $em->persist($this);
        $em->flush();
    }
}