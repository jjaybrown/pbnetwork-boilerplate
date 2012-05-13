<?php
use \App\Entity\UserActivity as UserActivity;

 class App_Action_Helper_PageViews extends \Zend_Controller_Action_Helper_Abstract
 {
     public function direct($module, $controller, $action, $params)
     {
         $em = \Zend_Registry::get('em');
         $pageViews = $em->getRepository("App\Entity\UserActivity")->pageViews($module, $controller, $action, UserActivity::filterParams($params));
         
         return $pageViews;
     }
 }
?>
