<?php

class App_Controller extends Zend_Controller_Action implements Zend_Acl_Resource_Interface
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
     * ACL Resource Id
     * @var mixed
     */
    private $_resourceId = "Controller";
    
    
    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_em = Zend_Registry::get('em');
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