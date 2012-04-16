<?php

namespace App;

class Controller extends \Zend_Controller_Action implements \Zend_Acl_Resource_Interface
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
     * @var Zend_Auth 
     */
    protected $_auth = null;
    
    /**
     * @var Cache 
     */
    protected $_cache = null;
    
    /**
     * ACL Resource Id
     * @var mixed
     */
    private $_resourceId = "Controller";
    
    
    public function init()
    {
        $this->_em = \Zend_Registry::get('em');
        $this->_auth = \Zend_Auth::getInstance();
        $this->_cache = \Zend_Registry::get('cache');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getCurrentMessages();
        
        // Set site meta data
        $this->view->title = "the Paintball Network";
    }
    
    public function getResourceId()
    {
        return $this->_resourceId;
    }
    
    public function setResourceId($id = "Controller")
    {
        // Set the resource id, make it uppercase for consistancy
        $this->_resourceId = ucwords($id);
    }

}