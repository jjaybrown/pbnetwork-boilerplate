<?php
use App\Controller as AppController;

class Basket_CheckoutController extends AppController
{

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
        parent::init();
    
        // Get shopping cart from session namespace
        $namespace = new \Zend_Session_Namespace('cart');
        $this->_cart = $namespace->cart;

        // Check if Cart has items
        if($this->_cart->numItemsInCart != 0){
            // Get the paypal gateway from the session
            if(\Zend_Session::namespaceIsset('paypal')){
                $namespace = new \Zend_Session_Namespace('paypal');
                $this->_paypal = $namespace->paypal;
            }else{ // or create and store in session
                $namespace = new \Zend_Session_Namespace('paypal');

                // Create new paypal gateway object
                $this->_paypal = new \App\Classes\Checkout\Gateways\Paypal(
                    "seller_1330298593_biz_api1.jbfreelance.co.uk",
                    "1330298621",
                    "AD4YlKHKo7SfxDaPWqwPESCGRwSPAsOsQt.YFf.2OzThbSujuH8xOM0M"
                );
                // Store gateway object in session namespace
                $namespace->paypal = $this->_paypal;
            }

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
        }else{ // No items
            // Redirect back to basket
            $this->_redirect('/basket/');
        }
    }

    /**
     * Checkout index page - wont be accessible in real world, used for debug
     */
    public function indexAction()
    {
        \Zend_Debug::dump($this->_cart);
        \Zend_Debug::dump($this->_paypal);
        \Zend_Debug::dump($this->_order);
    }

    /**
     * Paypal checkout process
     */
    public function paypalAction()
    {
        $this->render('index');
        
        switch($this->_request->getParam('type'))
        {
            case "SET":
                // Setup the paypal gateway and payment process
                $this->_paypalSetMethod();
                break;
            case "GET":
                // Obtain the customers details
                $this->_paypalGetMethod();
                break;
            case "DO":
                // Complete the payment
                $this->_paypalDoMethod();
                break;
        }
    }

    /**
     * Paypal SET API request wrapper
     */
    public function _paypalSetMethod()
    {
        // Check if this is a response request sent by API SET return URL
        if($this->_request->getParam('response'))
        {
            // Create a transaction 
            $this->_paypalTransaction($this->_cart->getStatus(), 'SET','', $this->_paypal->getRawSET());
            
            // Redirect to API GET request
            $this->_redirect('/basket/checkout/paypal/type/GET');
        }else{ // Initial API SET request
            // Reset paypal gateway as this may be another transaction attempt
            $this->_paypal->error = false;
            $this->_paypal->errorMessage = "";

            // Set cart status
            $this->_cart->setStatus('setting up payment');

            // Set cart payment method
            $this->_cart->setPaymentMethod($this->_paypal->name);

            // Save cart state
            $this->_cart->save();

            // Set payment type for order
            $this->_order->setPaymentMethod($this->_cart->getPaymentMethod());

            // Save order state
            $this->_order->save();

            // Setup the Express Checkout Transaction
            $this->_paypal->SetExpressCheckout($this->_cart->getSubTotal(), "http://localhost:8080/basket/checkout/paypal/type/SET/response/true", "http://localhost:8080/basket/checkout/", "GBP", "Sale");
        }
    }

    /**
     * Paypal GET API request wrapper
     */
    public function _paypalGetMethod()
    {
        // Set cart status
        $this->_cart->setStatus('getting customer details');

        // Save cart
        $this->_cart->save();

        // Get the Express Checkout Details of the buyer
        $response = $this->_paypal->GetExpressCheckoutDetails();

        // Check if the request was successful
        if(!$this->_paypal->error)
        {
            // Store details
            $this->_order->setFirstName(urldecode($response['FIRSTNAME']));
            $this->_order->setLastName(urldecode($response['LASTNAME']));
            $this->_order->setStreet(urldecode($response['SHIPTOSTREET']));
            $this->_order->setCity($response['SHIPTOCITY']);
            $this->_order->setCounty(urldecode($response['SHIPTOSTATE']));
            $this->_order->setPostCode(urldecode($response['SHIPTOZIP']));
            $this->_order->setCountry(urldecode($response['SHIPTOCOUNTRYNAME']));

            // Save order
            $this->_order->save();
            
            // Direct to pay
            $redirect = '/basket/checkout/paypal/type/DO';
        }else if($this->_paypal->error){ // An error occurred
            // Set cart status
            $this->_cart->setStatus('payment error');

            // Save cart
            $this->_cart->save();

            // Output error message to user
            $this->_flashMessenger->addMessage($this->paypal->_errorMessage);

            $redirect = '/basket/index/';
        }else{
            // Set cart status
            $this->_cart->setStatus('transaction failed');

            // Save cart
            $this->_cart->save();

            // Output error message to user
            $this->_flashMessenger->addMessage("Sorry something went wrong, please try again.");

            $redirect = '/basket/index/';
        }
        
        // Create a transaction 
        $this->_paypalTransaction($this->_cart->getStatus(), 'GET','', $this->_paypal->getRawGET());
        $this->_redirect($redirect);
    }

    /**
     * Paypal DO API request wrapper
     */
    public function _paypalDoMethod()
    {
        // Set cart status
        $this->_cart->setStatus('processing payment');

        // Save cart
        $this->_cart->save();

        // Finalise the PayPal transaction
        $transaction = $this->_paypal->DoExpressCheckoutPayment($this->_cart->getSubTotal(), "GBP", "Sale");

        // Check cart transaction was successful
        if(!$this->_paypal->error){
            // Set cart and order status
            $this->_cart->setStatus('paid');
            $this->_order->setStatus('paid');

            // Set whether the cart transation is comeplete or not
            $this->_cart->setComplete(true);

            // Set date and time of completion
            $this->_cart->setCompletedDate(new \DateTime());

            // Save cart
            $this->_cart->save();

            // Save Order
            $this->_order->save();
            
            // Reset cart and order
            $this->_cart->trash();
            
            $redirect = '/basket/checkout/complete';
        }else{ // Error occurred during transaction
            // Set cart status
            $this->_cart->setStatus('payment error');

            // Save cart
            $this->_cart->save();
            
            // Throw error message
            throw new \Zend_Exception($this->_paypal->errorMessage);
        }
        // Get transaction id
        $this->_paypalTransaction($this->_cart->getStatus(), 'DO','Transaction Id '.$transaction['TRANSACTIONID'], $this->_paypal->getRawGET());
        $this->_redirect($redirect);
    }

    /**
     * Creates a transaction record
     * @param string $status - Status of the transaction
     * @param string $type - Type of transaction being made. i.e. SET, GET, DO
     * @param string $notes - Additional information related to the transaction
     * @param mixed $raw - The RAW data dump returned from the gateway provider
     */
    protected function _paypalTransaction($status, $type, $notes, $raw)
    {
        // Create transaction
        $transaction = new App\Entity\Checkout\Transaction(
                $this->_order->getId(),
                $this->_cart->getPaymentMethod(),
                $status,
                $type,
                $notes,
                $raw
        );
        
        // Save transaction
        $this->_em->persist($transaction);
        $this->_em->flush();
    }

    /**
     * Checkout complete page
     */
    public function completeAction()
    {
        $this->render('index');
        
        $transactions =  $this->_em->getRepository("\App\Entity\Checkout\Transaction")->findBy(array('_orderId' => '22'));
        \Zend_Debug::dump($transactions);
        // Send confirmation email
        /*$mail = new \Zend_Mail();
        $mail->setSubject('Order Confirmation');
        $mail->setFrom('orders@thepaintballnetwork.co.uk');
        $mail->addTo('jason.brown.delta@gmail.com');
        $mail->setBodyText(
            'Thankyou for you recent order, please find your order details below: \n\n
             Order Number: '.$this->_order->getId().'\n\n
             Order Total: £'.$this->_cart->getSubTotal()
        );
        $mail->send();*/
    }
}