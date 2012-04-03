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
            $html= '
                    <li class="navbar-text dropdown">
                        <a href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown">
                            <i class="icon-user icon-white"></i> '. ucwords($username) .'
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">';
                  if($auth->getIdentity()->getRoleId() == \App\Acl::ADMIN)
                  {
                    $html .= '<li><a href="'.$this->view->url(
                            array(
                                "module" => "admin",
                                "controller" => "index",
                                "action" => "index"
                                )).'"><i class="icon-home"></i> Admin Panel</a></li>';
                  }
                  $html .= '<li><a href="'.$this->view->baseUrl('/profile/view').'"><i class="icon-user"></i> Profile</a></li>
                            <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="'.$logoutUrl.'"><i class="icon-lock"></i> Logout</a></li>
                        </ul>
                    </li>';
            return $html;
            
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
        return '<li><a href="'.$loginUrl.'">Login</a></li>';
    }
}
?>