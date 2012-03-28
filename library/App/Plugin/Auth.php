<?php

namespace App\Plugin;
use App\Acl as Acl;

class Auth extends \Zend_Controller_Plugin_Abstract
{
    protected $_acl;
    protected $_auth;
    
    public function __construct(Acl $acl)
    {
        $this->_acl = $acl;
        
        // Get instance of Auth
        $this->_auth = \Zend_Auth::getInstance();
    }
    
    public function isValidRequest(\Zend_Controller_Request_Abstract $request)
    {
        $dispatcher = \Zend_Controller_Front::getInstance()->getDispatcher();
        
        if($dispatcher->isDispatchable($request))
        {
            // Use reflection to check if the action exists
            $className = $dispatcher->getControllerClass($request);
            $fullClassName = $dispatcher->loadClass($className);
            $action = $dispatcher->getActionMethod($request);
            $class = new \Zend_Reflection_Class($fullClassName);
            
            return $class->hasMethod($action);
        }
    }
    
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {        
        // Only perform check against valid requests 
        if($this->isValidRequest($request))
        {
            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();
            $resource = $module.":".$controller;
            $role = $this->_acl->getCurrentRole();
            
            // Check if a user is logged and doesnt have access
            if($this->_auth->hasIdentity() && !$this->_acl->isAllowed($role, $resource, $action))
            {
                // Direct to unauthorised page
                $request->setModuleName('site');
                $request->setControllerName('auth');
                $request->setActionName('forbidden');

                // Redirect to login page
                $this->getResponse()->setHttpResponseCode(401);
            }elseif(!$this->_auth->hasIdentity() && !$this->_acl->isAllowed($role, $resource, $action)){
                // Not logged in trying to access a restricted page
                
                $fm = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $fm->addMessage(array('error' => 'You\'ve tried to access a restricted area'));
                
                $request->setModuleName('site');
                $request->setControllerName('auth');
                $request->setActionName('login');
                
                // Redirect to login page
                $this->getResponse()->setHttpResponseCode(403);
            }
        }
    }
}
?>
