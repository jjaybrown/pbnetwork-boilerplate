<?php
use App\Controller as AppController;
use App\Entity\Article as Article;
use App\Form\News\Add as AddNewsForm;

class News_IndexController extends AppController
{

    public function init()
    {
        parent::init();
    }
        
    public function indexAction()
    {
        $articles = $this->_em->getRepository("App\Entity\Article")->published();
        $this->view->articles = $articles;
    }
    
    public function addAction()
    {
        $addNewsForm = new AddNewsForm;
         if ($this->_request->isPost())
         {
            if($addNewsForm->isValid($this->_request->getPost())) 
            {
                // Get form data
                $data = $addNewsForm->getValues();
                
                // Get user posting
                $userId = $this->_auth->getIdentity()->getId();
                $user = $this->_em->find("App\Entity\User", $userId);
                
                // Create article
                $article = new Article($data['title'], $user, $data['summary'], $data['content']);
                
                //@TODO check if we're saving or publishing article
                $article->publish(true);
               
                // Save article
                try {
                    $this->_em->persist($article);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Published article successfully');
                    //$this->_redirect('/news/index/add');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    //$this->_redirect('/news/index/add');
                }
                
            }else{
                $addNewsForm->buildBootstrapErrorDecorators();
            }
        }

        $this->view->form = $addNewsForm;
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