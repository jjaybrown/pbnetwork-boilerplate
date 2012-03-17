<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->getUsername();
            $logoutUrl = $this->view->url(array(
                'module' => 'site',
                'controller'=>'auth',
                'action'=>'logout'), null, true);
            //return '<p class="navbar-text pull-right">Welcome <span style="color:#08C;">' . ucwords($username) .  '</span>. <a href="'.$logoutUrl.'">Logout</a></p>';
            return '<ul class="nav pull-right">
                    <li class="navbar-text pull-right dropdown">
                        <a href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown">
                            '. ucwords($username) .' <i class="icon-user icon-white"></i>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="'.$logoutUrl.'">Logout</a></li>
                        </ul>
                    </li>
                </ul>';
            
        } 

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array(
            'module' => 'site',
            'controller'=>'auth',
            'action'=>'index'));
        return '<p class="navbar-text pull-right"><a href="'.$loginUrl.'">Login</a></p>';
    }
}
?>