<?php

namespace App\Plugin;

class UserActivity extends \Zend_Controller_Plugin_Abstract
{
    private $_auth;
    
    public function __construct()
    {
        // Get instance of auth object
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
            // Get logged in user object
            $user = $this->_auth->getIdentity();
            // Check object is valid
            if(!is_null($user))
            {
                // Get entity manager
                $em = \Zend_Registry::get('em');
                
                // Get user entity from auth user
                $user = $em->find("\App\Entity\User", $user->getId());
                
                // Log user activity
                $activity = new \App\Entity\UserActivity($user, $request);

                try{
                    // Save user activity
                    $em->persist($activity);
                    $em->flush();
                }catch (Exception $e){
                    // Log error
                    \Zend_Debug::dump($e->getMessage());die;
                }
            }
        }
    }
}
?>