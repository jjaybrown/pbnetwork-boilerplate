<?php

class Site_IndexController extends Zend_Controller_Action
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc = null;

    /**
     * @var \App\Service\RandomQuote
     * @InjectService RandomQuote
     */
    protected $_randomQuote = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
    }

    public function searchAction()
    {
        if ($query = $this->getRequest()->getParam('query')) {
            $resultSet = array();

            try {
                $client = Zend_Registry::get('es');
                $index = $client->getIndex('quotes');
                $type = $index->getType('quote');
                $resultSet = $type->search($query);
            } catch (Exception $e) {
                $this->_redirect('/');
            }

            $data = array();

            foreach ($resultSet as $result) {
                $hit = $result->getHit();
                $quote = new \App\Entity\Quote();
                $quote->setAuthor($hit['_source']['author']);
                $quote->setWording($hit['_source']['wording']);
                $quote->setSource($hit['_source']['source']);
                $data[] = $quote;
            }

            $this->view->search = true;
            $this->view->data = $data;
            $this->_helper->viewRenderer('index');
        }
        else
            $this->_redirect('/');

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
                    'pages' => array(
                        array(
                            'action' => 'index',
                            'controller' => 'roundup',
                            'module' => 'community',
                            'label' => 'Roundup'
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'forums',
                            'module' => 'community',
                            'label' => 'Forums'
                        ),
                        array(
                            'action' => 'index',
                            'controller' => 'groups',
                            'module' => 'community',
                            'label' => 'Groups'
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

        $this->view->navigation($container);
    }

    public function footerAction()
    { 
        $cache = Zend_Registry::get('cache');

        if ($cache->contains('timestamp')) {
            $timestamp = $cache->fetch('timestamp');
            $this->view->cachedTimestamp = true;
        } else {
            $timestamp = time();
            $cache->save('timestamp', $timestamp);
        }

        $this->view->timestamp = $timestamp;
    }
}