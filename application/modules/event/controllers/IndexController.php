<?php

class Event_IndexController extends Zend_Controller_Action
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
    }

    public function indexAction()
    {
        /*$newEvent = new \App\Entity\Event();
        $newEvent->setName("My Birthday");
        $newEvent->setStart(new \DateTime("2012-08-05 12:00:00"));
        $newEvent->setEnd(new \DateTime("2012-08-05 23:00:00"));
        
        try {
            $this->_em->persist($newEvent);
            $this->_em->flush();
        }
        catch (Exception $e) {
            $this->_redirect('/');
        }*/
        
        $data = $this->_em->getRepository("\App\Entity\Event")->find(1);
        Zend_Debug::dump($data);
        Zend_Debug::dump($data->getName());
        Zend_Debug::dump($data->getNumTickets());
        $data->setNumTickets(5);
        $data->removeTickets(3);
        $this->_em->flush();
        Zend_Debug::dump($data->getNumTickets());
    }

}





