<?php

class Basket_IndexController extends Zend_Controller_Action
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

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $namespace = new \Zend_Session_Namespace('cart');
        $this->_cart = $namespace->cart;
    }

    public function indexAction()
    {
        Zend_Debug::dump($this->_cart);
        $cartForm = $this->_setupBasketForm();

        $currency = Zend_Registry::get('currency');
        $this->view->basketForm = $cartForm;
        $this->view->currency = $currency;
        $this->view->subTotal = $this->_cart->getSubTotal();
        $this->view->total = $this->_cart->getTotal();
        $this->view->items = $this->_cart->getItems();
    }

    /*public function addAction()
    {
        $item = new \App\Classes\Cart\Item('001', 'apple', '0.79');
        $this->_cart->addItem($item);
        $this->_redirect('/basket/index');
    }*/

    public function updateAction()
    {   
        // Basket been updated
        if($this->_request->isPost()){
            $data = $this->_request->getPost();
            
            // Update item quantities
            foreach($this->_cart->getItems() as $item){
                // If item has a quantity field posted
                if(isset($data['quantity_'.$item->code])){
                    $item->setQuantity($data['quantity_'.$item->code]);
                }
            }

            // Update entire cart
            $this->_cart->updateCart();

            // Redirect back to basket 
            $this->_redirect('/basket/index');
        }

        $this->render('index');
    }

    public function removeAction()
    {
        // Get item id
        $code = $this->_request->getParam('code');
        if(!is_null($code)){
            // Remove only this item
            $this->_cart->removeItem($code);
            $this->_redirect('/basket/index');
        }
    }

    public function emptyAction()
    {
        // Empty cart
        $this->_cart->clearCart();
        $this->_flashMessenger->addMessage(array('info' => 'Emptied your shopping cart'));
        $this->_redirect('/basket/index');
    }

    // Test function to remove cart and payment
    public function trashAction()
    {
        // Empty cart
        $this->_cart->trash();
        \Zend_Session::namespaceUnset('paypal');
        $this->_redirect('/basket/index');
    }

    /**
     * Creates the shopping basket form
     * @return Zend Form cartForm - the cart form
     */
    protected function _setupBasketForm()
    {
        $cartForm = new \EasyBib_Form;
        $cartForm->setAction('/basket/index/update')
                ->setMethod('post')
                ->setName('cart');

        // Create form elements for each item
        foreach($this->_cart->getItems() as $item){
            // Create Quantity selector
            $quantity = new \Zend_Form_Element_Select('quantity_'.$item->code);

            // Set default value to the existing quantity of the item
            $quantity->setValue($item->getQuantity());

            $quantity->setAttrib('onChange', 'document.cart.submit()');
            // Check we have enough tickets to display our max purchase amount
            $max_purchase_amount = \Zend_Registry::get('max_purchase_amount');

            // Get the event information
            $event = $this->_em->getRepository("\App\Entity\Event")->find($item->getEventId());

            if($event->getNumTickets() >= $max_purchase_amount)
            {
                for($i = 1; $i <= $max_purchase_amount; $i++)
                {
                    $quantity->addMultiOption($i, $i);
                }
            }else{
                for($i = 1; $i <= $event->getNumTickets(); $i++)
                {
                    $quantity->addMultiOption($i, $i);
                }
            }
            
            // Add element to form
            $cartForm->addElement($quantity);
        }

        $submit = new \Zend_Form_Element_Submit('update');
        $cartForm->addElement($submit);

        return $cartForm;
    }

}