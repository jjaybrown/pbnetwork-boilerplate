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
     * @var App\Entity\Checkout\Order $_order
     */

    protected $_order = null;

    /**
     * Paypal Gateway
     * @var App\Classes\Checkout\Gateways\Paypal 
     */
    protected $_paypal = null;

    /**
     * initilize checkout process
     */
    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

        // Get the paypal gateway from either the session or create and store in session
        if(\Zend_Session::namespaceIsset('paypal')){
            $namespace = new \Zend_Session_Namespace('paypal');
            $this->_paypal = $namespace->paypal;
        }else{
            $namespace = new \Zend_Session_Namespace('paypal');
            $this->_paypal = new \App\Classes\Checkout\Gateways\Paypal("seller_1330298593_biz_api1.jbfreelance.co.uk", "1330298621", "AD4YlKHKo7SfxDaPWqwPESCGRwSPAsOsQt.YFf.2OzThbSujuH8xOM0M");
            $namespace->paypal = $this->_paypal;
        }

        // Get shopping cart from session namespace
        $namespace = new \Zend_Session_Namespace('cart');
        $this->_cart = $namespace->cart;
        
        // Save shopping cart
        $this->_cart = $this->_cart->save();

        // Get the order from either the session or create one - DONT GET FROM NAMESPACE USE DOCTRINE PERSIST
        $this->_order = $this->_em->getRepository("\App\Entity\Checkout\Order")->findOneBy(array('_cartId' => $this->_cart->getId()));
        // Check of an order already exists for this cart
        if(is_null($this->_order)){
            // Create order
            $this->_order = new \App\Entity\Checkout\Order($this->_cart->getId(), 'pending payment', $this->_cart->getItems());
        }else{
            // Update order record
            $this->_order->setItems($this->_cart->getItems());
        }

        $this->_order->save();
    }

    public function indexAction()
    {
        \Zend_Debug::dump($this->_cart);
        \Zend_Debug::dump($this->_paypal);
        \Zend_Debug::dump($this->_order);
    }

    public function paypalAction()
    {
        \Zend_Debug::dump($this->_paypal);
        $this->render('index');
        
        switch($this->_request->getParam('type'))
        {
            case "SET":
                // Set cart status
                $this->_cart->setStatus('setting up payment');

                // Set cart payment method
                $this->_cart->setPaymentMethod('paypal');

                // Save cart state
                $this->_cart->save();

                // Set payment type for order
                $this->_order->setPaymentMethod($this->_cart->getPaymentMethod());

                // Save order state
                $this->_order->save();

                // Setup the Express Checkout Transaction
                $this->_paypal->SetExpressCheckout($this->_cart->getSubTotal(), "http://localhost:8080/basket/checkout/paypal/type/GET", "http://localhost:8080/basket/checkout/");
                break;
            case "GET":
                // Set cart status
                $this->_cart->setStatus('getting customer details');

                // Get the Express Checkout Details of the buyer
                $response = $this->_paypal->GetExpressCheckoutDetails();

                // Check if the request was successful
                if($response)
                {
                    // Store details
                    $this->_order->setFirstName($response['FIRSTNAME']);
                    $this->_order->setLastName($response['LASTNAME']);
                    $this->_order->setStreet(urldecode($response['SHIPTOSTREET']));
                    
                    $this->_order->save();
                    Zend_Debug::dump($this->_order);
                    Zend_Debug::dump($this->_order->save());
                   
                }else if($this->_paypal->error){ // An error occurred
                    // Set cart status
                    $this->_cart->setStatus('payment error');
                    // Output error message to user
                    $this->_flashMessenger->addMessage($this->paypal->_errorMessage);
                    $this->_redirect('/basket/index/');
                }else{
                    // Set cart status
                    $this->_cart->setStatus('transaction failed');
                    // Output error message to user
                    $this->_flashMessenger->addMessage("Sorry something went wrong, please try again.");
                    $this->_redirect('/basket/index/');
                }
                break;
            case "DO":
                // Set cart status
                $this->_cart->setStatus('processing payment');

                // Finalise the PayPal transaction
                $success = $this->_paypal->DoExpressCheckoutPayment($this->_cart->getSubTotal());

                // Check cart transaction was successful
                if($success){
                    // Check status of payment
                    

                    // Set cart status
                    $this->_cart->setStatus('paid');
                    // Set whether the cart transation is comeplete or not
                    $this->_cart->setComplete(true);
                    // Set date and time of completion
                    $this->_cart->setCompletedDate(new \DateTime());
                }else{
                    // Error occurred during transaction
                    // Set cart status
                    $this->_cart->setStatus('error');
                }
                break;
        }
    }
}