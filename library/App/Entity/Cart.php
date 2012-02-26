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
    /** @Column(type="string", name="transaction_id", length="255", unique=true) */
    private $_transaction_id = 1;// Temp must assign
    /** @Column(type="string", name="session_id", length="255", unique=true) */
    private $_session_id = "";
    /** @Column(type="boolean", name="complete") */
    private $_complete = false;
    /** @Column(type="datetime", name="completed_on", nullable="true") */
    private $_completed;
    /** @Column(type="string", name="payment_method", length="255") */
    private $_payment_method ="";
    /** @Column(type="decimal", name="subtotal") */
    private $_sub_total = 0;
    /** @Column(type="decimal", name="total") */
    private $_total = 0;
    /** @Column(type="string", name="status") */
    private $_status = "pending";

    private $_items = array();
    public $numItemsInCart = 0;
    
    public static function init($save = false){
       // Check if an existing cart session exists
       if(\Zend_Session::namespaceIsset('cart'))
       {
           $session = new \Zend_Session_Namespace('cart');
           $cart = $session->cart;
       }else{
           // No existing cart session, create a new one
           $cart = new Cart();
           $session = new \Zend_Session_Namespace('cart');
           $cart->setSessionId(\Zend_Session::getId());
           $session->cart = $cart;
           if($save)
           {
               // Save cart and it's session
               $cart->_save();
           }
       }
       
       return $cart;
    }

    public function trash()
    {
        // Remove Cart namespace from session
        \Zend_Session::namespaceUnset('cart');
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
    
    public function getTotal(){
        return $this->_total;
    }
    
    public function getStatus(){
        return $this->_status;
    }
    
    public function setStatus($status){
        $this->_status = $status;
        return $this;
    }

    public function addItem(\App\Classes\Cart\Item $i){
        $exists = false;
        // Check if item is already in cart
        foreach($this->_items as $item){
            // Find existing item and increment it's quantity
            if($item->code == $i->code){
                // Item already exists in cart
                $exists = true;
                
                // Check new quantity doesn't exceed item purchase limit
                $limit = \Zend_Registry::get('max_purchase_amount');
                if(($item->getQuantity() + $i->getQuantity()) <= $limit)
                {
                    $item->addQuantity($i->getQuantity());
                }
                
                break;
            }
        }

        // Item doesn't already exist in cart
        // Add to cart
        if(!$exists){
            array_push($this->_items, $i);
        }

        // Update cart
        $this->updateCart();
    }

    /*public function removeItem(\App\Classes\Cart\Item $i){
        foreach($this->_items as $key => $item){
            // Find our item in cart items
            if($item->code == $i->code){
                // Once we find the item by code, remove it from array
                unset($this->_items[$key]);
                // Re-assign key values
                array_values($this->_items);
                break;
            }
        }

        // Update number of items in cart
        $this->updateItemsInCartCount();
        // Re-calculate the carts sub total and total
        $this->calcSubTotal();
        $this->calcTotal();
    }*/

    public function removeItem($code){
        foreach($this->_items as $key => $item){
            // Find our item in cart items
            if($item->code == $code){
                // Once we find the item by code, remove it from array
                unset($this->_items[$key]);
                // Re-assign key values
                array_values($this->_items);
                break;
            }
        }

        // Update cart
        $this->updateCart();
    }

    public function clearCart(){
        $this->_items = array();
        $this->_sub_total = 0;
        $this->_total = 0;
        $this->numItemsInCart = 0;
    }

    public function calcSubTotal(){
        // Do we have any items in the cart?
        if(sizeof($this->_items) > 0){
            $sub = 0;
            foreach($this->_items as $item){
               $sub  += ($item->getQuantity() * $item->getPrice());
            }
            
            $this->_sub_total = $sub;
        }else{
            // If not sub total should be zero
            $this->_sub_total = 0;
        }

        return $this->_sub_total;
    }

    public function calcTotal(){
        // Get postage and other costs and apply to sub total
        return $this->_total;
    }

    public function updateItemsInCartCount(){
        $this->numItemsInCart = 0;

        foreach($this->_items as $item){
            $this->numItemsInCart += $item->getQuantity();
        }

        return $this->numItemsInCart;
    }

    public function getItems(){
        return $this->_items;
    }

    /**
     * Update entire cart
     */
    public function updateCart(){
         // Update number of items in cart
        $this->updateItemsInCartCount();
        // Re-calculate the carts sub total and total
        $this->calcSubTotal();
        $this->calcTotal();
    }
}