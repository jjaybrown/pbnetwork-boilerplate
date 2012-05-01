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
        $recentPosts = $this->_em->getRepository("App\Entity\Community\Post")->recentActivity();
        $this->view->recentPosts = $recentPosts;
        
        // Get latest user
        $this->view->latestUser = $this->_em->getRepository("App\Entity\User")->latestUser();
        
        // Get post count
        $this->view->postCount = $this->_em->getRepository("App\Entity\Community\Post")->count();
        
        // Get thread count
        $this->view->threadCount = $this->_em->getRepository("App\Entity\Community\Thread")->count();
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
                                ),
                                array(
                                 'module' => 'forum',
                                 'controller' => 'post',
                                 'action' => 'add' 
                                )
                            )
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'group',
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