<?php
use App\Controller as AppController;

class Community_IndexController extends AppController
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
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'index',
                            'module' => 'community',
                            'label' => 'Community Roundup'
                        ),
                        array(
                            'module' => 'forum',
                            'label' => 'Forums',
                            'pages' => array(
                                array(
                                    'module' => 'forum',
                                    'controller' => 'thread',
                                    'action' => 'view'
                                ),
                                array(
                                 'module' => 'forum',
                                 'controller' => 'post',
                                 'action' => 'index' 
                                )
                            )
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'groups',
                            'module' => 'community',
                            'label' => 'Groups'
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'competitions',
                            'module' => 'community',
                            'label' => 'Weekly Competitions'
                        )
                    )
                ),
                array(
                    'action'     => 'index',
                    'controller' => 'index',
                    'module'     => 'magazine',
                    'label'      => 'Paintball Scene Magazine'
                )
            )
        );
        \Zend_Registry::set("community_nav", $container);
        $this->view->navigation($container);
    }

}