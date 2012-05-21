<?php
use App\Controller as AppController;

class Community_ExchangeController extends AppController
{

    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('exchange');
    }
        
    public function viewAction()
    {
        $item = new App\Entity\Community\Exchange\Item("Dye", "Rotor", 68.99);
        \Zend_Debug::dump($item);
        
        $namespace = new \Zend_Session_Namespace('cart');
        $cart = $namespace->cart;
        
        $cart->addItem($item);
        $this->_redirect('/basket');
    }
    
    public function addAction()
    {
        
    }
}