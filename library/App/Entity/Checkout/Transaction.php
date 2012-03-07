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
    /** @Column(type="integer"), name="order_id") */
    private $_orderId;
    /** @Column(type="string"), name="gateway") */
    private $_gateway;
    /** @Column(type="string"), name="status") */
    private $_status;
    /** @Column(type="string"), name="type") */
    private $_type;
    /** @Column(type="array"), name="raw_data") */
    private $_rawData;
    /** @Column(type="datetime"), name="created") */
    private $_created;

    public function __construct($orderId, $gateway, $status, $type, $rawData)
    {
        $this->_orderId = $orderId;
        $this->_gateway = $gateway;
        $this->_status = $status;
        $this->_type = $type;
        $this->_rawData = $rawData;
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

    public function getType()
    {
        return $this->_type;
    }

    public function setType($type)
    {
        $this->_type = $type;
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