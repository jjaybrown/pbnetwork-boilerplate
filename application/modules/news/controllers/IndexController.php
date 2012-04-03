<?php
use App\Controller as AppController;

class News_IndexController extends AppController
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
                    'module'     => 'site',
                    'label'      => 'Home'
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'news',
                    'label'      => 'News',
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'index',
                            'module' => 'news',
                            'label' => 'Latest News'
                        ),
                        array(
                            'action' => 'archive',
                            'controller' => 'index',
                            'module' => 'news',
                            'label' => 'Archive'
                        )
                    )
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'event',
                    'label'      => 'Events',
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'calendar',
                            'module' => 'event',
                            'label' => 'Calendar'
                        )
                    )
                ),
                array(
                    'action'        => 'index',
                    'controller'    => 'index',
                    'module'        => 'community',
                    'label'      => 'Community',
                    'active' => true,
                ),
                array(
                    'action'     => 'index',
                    'controller' => 'index',
                    'module'     => 'magazine',
                    'label'      => 'Paintball Scene Magazine'
                )
            )
        );
        $this->view->navigation($container);
    }

}