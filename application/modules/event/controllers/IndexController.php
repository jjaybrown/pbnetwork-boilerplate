<?php

class Event_IndexController extends Zend_Controller_Action
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;
    protected $_cartNamespace = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_cartNamespace = new Zend_Session_Namespace('cart');
    }

    public function indexAction()
    {
        /*$newEvent = new \App\Entity\Event(
                "My Birthday",
                "2012-08-05 12:00:00",
                "2012-08-05 23:00:00",
                "Vodka Revolution",
                5
        );


      try {
            $this->_em->persist($newEvent);
            $this->_em->flush();
        }
        catch (Exception $e) {
            $this->_redirect('/');
        }*/
        $this->_cartNamespace->cart = \App\Entity\Cart::init();
        Zend_Debug::dump($this->_cartNamespace->cart);
        Zend_Debug::dump(Zend_Session::getId());
    }

    public function addAction()
    {
        Zend_Debug::dump($this->_cartNamespace->cart);
        //$this->_cartNamespace->cart->clearCart();
        //$apple = new App\Classes\Item("a1","apple",0.80);
        //$this->_cartNamespace->cart->addItem($apple);
        //$this->_cartNamespace->cart->addItem($apple);
        $eventData = $this->_em->getRepository("\App\Entity\Event")->find(1);
        $ticket = new \App\Classes\Ticket($eventData->getId(), $eventData->getName(), $eventData->getPrice());
        $ticket->setPrice(16.00);
        //Zend_Debug::dump($ticket);
        //$this->_cartNamespace->cart->addItem($ticket);
        Zend_Debug::dump($this->_cartNamespace->cart);
        Zend_Debug::dump($this->_cartNamespace->cart->updateItemsInCartCount());
        //$this->_cartNamespace->cart->removeItem($apple);
        //Zend_Debug::dump($this->_cartNamespace->cart);
        //$this->_cartNamespace->cart->trash();
        $this->_helper->viewRenderer('index');
    }

}