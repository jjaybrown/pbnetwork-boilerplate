<?php
namespace App\Entity;

/**
 *@Entity(repositoryClass="App\Repository\Cart")
 * @Table(name="cart") 
 */

class Cart
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue 
     */
    private $_id;
    /** @Column(type="string", name="customer_id") */
    private $_customer_id = 0;// Temp must assign
    /** @Column(type="string", name="transaction_id", length="255") */
    private $_transaction_id = 0;// Temp must assign
    /** @Column(type="string", name="session_id", length="255") */
    private $_session_id;
    /** @Column(type="boolean", name="complete") */
    private $_complete = false;
    /** @Column(type="datetime", name="completed_on") */
    private $_completed;
    /** @Column(type="string", name="payment_method", length="255") */
    private $_payment_method;
    /** @Column(type="decimal", name="subtotal") */
    private $_sub_total;
    /** @Column(type="decimal", name="total") */
    private $_total;
    /** @Column(type="string", name="status") */
    private $_status = "pending";
    
    public static function init($save = false){
       $cart = new Cart();
       if($save)
       {
           $cart->setSessionId(\Zend_Session::getId());
           $cart->_save();
       }
       return $cart;
    }
    
    public function _save(){
        $em = \Zend_Registry::get('em');
        $em->persist($this);
        $em->flush();
    }
    
    public function getId(){
        return $this->_id;
    }
    
    public function getCustomerId(){
        return $this->_customer_id;
    }
    
    public function setCustomerId($id){
        $this->_customer_id = $id;
        return $this;
    }
    
    public function getTransactionId(){
        return $this->_transaction_id;
    }
    
    public function setTransactionId($id){
        $this->_transaction_id = $id;
        return $this;
    }
    
    public function getSessionId(){
        return $this->_session_id;
    }
    
    public function setSessionId($id){
        $this->_session_id = $id;
        return $this;
    }
    
    public function isComplete(){
        return $this->_complete;
    }
    
    public function setComplete($complete){
        $this->_complete = $complete;
        return $this;
    }
    
    public function getCompleted(){
        return $this->_completed;
    }
    
    public function setCompleted(\DateTime $completed){
        $this->_completed = $completed;
        return $this;
    }
    
    public function getPaymentMethod(){
        return $this->_payment_method;
    }
    
    public function setPaymentMethod($payment){
        $this->_payment_method = $payment;
        return $this;
    }
    
    public function getSubTotal(){
        return $this->_sub_total;
    }
    
    public function calcSubTotal(){
        
        return $this;
    }
    
    public function getTotal(){
        return $this->_total;
    }
    
    public function calcTotal(){
        
        return $this;
    }
    
    public function getStatus(){
        return $this->_status;
    }
    
    public function setStatus($status){
        $this->_status = $status;;
        return $this;
    }
            
}