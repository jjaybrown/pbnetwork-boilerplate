<?php
namespace App\Entity\Checkout;


/**
 *@Entity(repositoryClass="App\Repository\Checkout\Transaction")
 * @Table(name="transactions")
 */

class Transaction
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue 
     */
    private $_id;
    /** @Column(type="integer", name="order_id") */
    private $_orderId;
    /** @Column(type="string", name="gateway") */
    private $_gateway;
    /** @Column(type="string", name="status") */
    private $_status;
    /** @Column(type="string", name="action") */
    private $_action;
    /** @Column(type="string", name="notes") */
    private $_notes;
    /** @Column(type="array", name="raw_data") */
    private $_rawData;
    /** @Column(type="datetime", name="created") */
    private $_created;

    public function __construct($orderId, $gateway, $status, $action, $notes, $rawData)
    {
        $this->_orderId = $orderId;
        $this->_gateway = $gateway;
        $this->_status = $status;
        $this->_action = $action;
        $this->_notes = $notes;
        $this->_rawData = $rawData;
        $this->_created = new \DateTime;
    }

    public function getOrderId()
    {
        return $this->_orderId;
    }

    public function setOrderId($id)
    {
        $this->_orderId = $id;
        return $this;
    }

    public function getGateway()
    {
        return $this->_gateway;
    }

    public function setGateway($gateway)
    {
        $this->_gateway = $gateway;
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
    
    public function getNotes()
    {
        return $this->_notes;
    }
    
    public function setNotes($notes)
    {
        $this->_notes = $notes;
        return $this;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    public function getRawData()
    {
        return $this->_rawData;
    }

    public function setRawData($data)
    {
        $this->_rawData = $data;
        return $this;
    }
}