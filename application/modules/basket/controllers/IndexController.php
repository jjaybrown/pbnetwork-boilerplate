<?php

class Basket_IndexController extends Zend_Controller_Action
{
    protected $_cart = null;
    
    public function init()
    {
        $namespace = new \Zend_Session_Namespace('cart');
        $this->_cart = $namespace->cart;
    }

    public function indexAction()
    {
        Zend_Debug::dump($this->_cart);
        $currency = Zend_Registry::get('currency');
        $this->view->currency = $currency;
        $this->view->subTotal = $this->_cart->getSubTotal();
        $this->view->total = $this->_cart->getTotal();
        $this->view->items = $this->_cart->getItems();
    }

    public function addAction()
    {
        $item = new \App\Classes\Cart\Item('001', 'apple', '0.79');
        $this->_cart->addItem($item);
        $this->_redirect('/basket/index');
    }

    public function removeAction()
    {
    }

    public function emptyAction()
    {
        $this->_cart->clearCart();
        $this->_redirect('/basket/index');
    }
}