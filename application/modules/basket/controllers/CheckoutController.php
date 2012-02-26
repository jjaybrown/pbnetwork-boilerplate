<?php

class Basket_CheckoutController extends Zend_Controller_Action
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;
    /**
     * @var Zend_Controller_Action_Helper
     */
    protected $_flashMessenger = null;
    /**
     * @var App\Entity\Cart $_cart 
     */
    protected $_cart = null;
    
    /**
     * Paypal Gateway
     * @var App\Classes\Checkout\Gateways\Paypal 
     */
    protected $_paypal = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $namespace = new \Zend_Session_Namespace('cart');
        $this->_cart = $namespace->cart;

        // Get the paypal gateway from either the session or create and store in session
        if(\Zend_Session::namespaceIsset('paypal')){
            $namespace = new \Zend_Session_Namespace('paypal');
            $this->_paypal = $namespace->paypal;
        }else{
            $namespace = new \Zend_Session_Namespace('paypal');
            $this->_paypal = new \App\Classes\Checkout\Gateways\Paypal("seller_1330298593_biz_api1.jbfreelance.co.uk", "1330298621", "AD4YlKHKo7SfxDaPWqwPESCGRwSPAsOsQt.YFf.2OzThbSujuH8xOM0M");
            $namespace->paypal = $this->_paypal;
        }
    }

    public function indexAction()
    {
        \Zend_Debug::dump($this->_paypal);
    }

    public function paypalAction()
    {
        switch($this->_request->getParam('type'))
        {
            case "SET":
                // Setup the Express Checkout Transaction
                $this->_paypal->SetExpressCheckout($this->_cart->getSubTotal(), "http://new.jbfreelance.co.uk/basket/checkout/", "http://localhost:8080/basket/checkout/");
                break;
            case "GET":
                // Get the Express Checkout Details of the buyer
                $details = $this->_paypal->GetExpressCheckoutDetails();

                // Store details
                break;
            case "DO":
                // Finalise the PayPal transaction
                $this->_paypal->DoExpressCheckoutPayment($this->_cart->getTotal());
                break;
        }
    }
}