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
        
    }

    public function addAction()
    {
        $this->_helper->viewRenderer('index');
    }

}