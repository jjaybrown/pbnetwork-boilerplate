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
    
    protected $_facebook = null;
    
    public function init($options = null)
    {
        $this->_em = \Zend_Registry::get('em');
        $this->_auth = \Zend_Auth::getInstance();
        $this->_cache = \Zend_Registry::get('cache');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();
        
        if(isset($options['facebook']) && $options['facebook'])
        {
            $this->_facebook = new \App\Classes\Facebook\Facebook(array(
                'appId'  => '226380840801531',
                'secret' => 'b6745fc6bc55920225849f73e84a9cf2',
            ));
        }
        
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
    
    /**
     * Creates a notification to be displayed
     * @param type $heading
     * @param type $message 
     */
    protected function notification($heading, $message)
    {
        // Create a session and store new notification
        $notification = new \Zend_Session_Namespace('notification');
        $notification->heading = $heading;
        $notification->message = $message;
        
        // Redirect to the notification page
        $this->_helper->redirector('notification', 'index');
    }

}