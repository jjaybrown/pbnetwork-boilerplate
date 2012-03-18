<?php

use App\Controller as AppController;

class Admin_IndexController extends AppController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        
    }

    public function headerAction()
    {
        $container = new Zend_Navigation(
            array(
                array(
                    'action'     => 'index',
                    'controller' => 'index',
                    'module'     => 'admin',
                    'label'      => 'Admin Home'
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'news',
                    'module'        => 'admin',
                    'label'      => 'News',
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'event',
                    'module'        => 'admin',
                    'label'      => 'Events',
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'community',
                    'module'        => 'admin',
                    'label'      => 'Community',
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'user',
                    'module'        => 'admin',
                    'label'      => 'Users',
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'order',
                    'module'        => 'admin',
                    'label'      => 'Orders',
                )
            )
        );

        $this->view->navigation($container);
    }

    public function clearcacheAction()
    {
        $this->_cache->deleteAll();
        $this->_helper->redirector('index', 'index');
    }


}





