<?php
namespace App\Classes;

class Cart
{
    private $cookie_id = 'CART';
    protected $items = array();
    public $session = null;

    public function __construct($create = true){
        // Create new cart if it doesn't already exist
        if($create){
            // Create new session
            $session = new \Zend_Session_Namespace($this->cookie_id,true);
            // Assign this instance of Cart to session
            $session->cart->$this;
            // Now lock the session to prevent write access to it
            //$session->lock();
            // Store session in cart
            $this->session = $session;
        }else{
            // Use existing Cart object
            $session = new \Zend_Session_Namespace($this->cookie_id,true);
            \Zend_Debug::dump($session->cart);
            if($session->cart instanceof Cart)
            {
                $this->session = $session;
                \Zend_Debug::dump($this);
            }else{
                return $this;
            }
        }
        return $this;
    }

    public function getItems(){
        return $this->items;
    }

    public function addItem(Item $item){
        // Add item to Cart Items
        array_push($this->items, $item);
        // Unlock session for writing
        //$this->session->unlock();
        // Store modified Cart in session
        $this->session = $this;
        // Lock session to prevent writing
        //$this->session->lock;
        // Return items in cart
        return $this->items;
    }
}
