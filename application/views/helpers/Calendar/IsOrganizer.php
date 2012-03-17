<?php

class Zend_View_Helper_IsOrganizer extends Zend_View_Helper_Abstract
{
    public function IsOrganizer()
    {
        $auth = Zend_Auth::getInstance();
        
        // Check for logged in user
        if($auth->hasIdentity())
        {
            // Does this user have access as an organizer
            if($auth->getIdentity()->getRoleId() == "Organizer" || $auth->getIdentity()->getRoleId() == "Admin")
            {
            }
        }
    }
}
?>