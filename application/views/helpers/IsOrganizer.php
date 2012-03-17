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
            if($auth->getIdentity()->getRoleId() == \App\Acl::ORGANIZER 
                    || $auth->getIdentity()->getRoleId() == \App\Acl::ADMIN)
            {
                $html = 
                "
                    <div class='well pull-right' style='padding:8px 0;>
                        <ul class='nav nav-list'>
                            <li class='nav-header'>Event Organizer Menu</li>
                            <li><i class='icon-edit'></i><a href='#'>Create event</a></li>
                        </ul>
                    </div>
                ";
                return $html;
            }
        }
    }
}
?>