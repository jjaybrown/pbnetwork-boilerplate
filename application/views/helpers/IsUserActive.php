<?php

/**
 * Displays the number of active users 
 */
class Zend_View_Helper_IsUserActive extends Zend_View_Helper_Abstract 
{
    /*
     * Interval to check for user activity within
     * Default : 10min
     */
    public static $interval = 10; // Minutes
    
    public function IsUserActive($user)
    {
        // Get user's activity
        $em = \Zend_Registry::get('em');
        $activity = $em->getRepository("\App\Entity\Tracking\UserActivity")->findByInterval($user->getId(), self::$interval);
        
        // Check if any records are returned
        if(count($activity) > 0)
        {
            return true;
        }
        
        return false;
    }
}
?>