<?php

class Zend_View_Helper_IsLoggedIn extends Zend_View_Helper_Abstract 
{
    public function IsLoggedIn()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            return true;
        }
        
        return false;
    }
}
?>