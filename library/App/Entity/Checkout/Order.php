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
    
    /** @Column(type="integer"), name="cart_id") */
    private $_cartId;
    /** @Column(type="string", name="order_status", length="50") */
    private $_status = "";
    /** @Column(type="string", name="payment_method", length="50") */
    private $_paymentMethod = "";
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
    /** @Column(type="array", name="items") */
    private $_items = array();
    /**
     * Constructor
     */
    public function __construct($cartId, $status, $items = array()){
        $this->_cartId = $cartId;
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
        $this->_items = $items;
    }

    public function getCartId()
    {
        return $this->_cartId;
    }

    public function setCartId($id)
    {
        $this->_cartId = $id;
        return $this;
    }
    
    public function getStatus()
    {
        return $this->_status;
    }
    
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->_paymentMethod;
    }

    public function setPaymentMethod($method)
    {
        $this->_paymentMethod = $method;
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

    public function getItems()
    {
        return $this->_items;
    }

    public function setItems(array $items)
    {
        $this->_items = $items;
        return $this;
    }

    public function save()
    {
        // Get entity manager
        $em = \Zend_Registry::get('em');

        // Check if this order exists
        $order = $em->find('App\Entity\Checkout\Order', $this->_id);
        
        // Order doesnt exist
        if(is_null($order)){
            $em->persist($this);
            $em->flush();
            $order = $em->find('App\Entity\Checkout\Order', $this->_id);
        }else{
            $order = $em->merge($this);
            $em->persist($order);
            $em->flush();
        }
        
        return $order;
    }
}