<?php
use App\Controller as AppController;
use App\Entity\Article as Article;
use App\Form\News\Add as AddNewsForm;
use App\Form\News\Edit as EditNewsForm;

class News_IndexController extends AppController
{

    public function init()
    {
        parent::init();
    }
    
    public function preDispatch()
    {
        $action = $this->getRequest()->getActionName();
        
        switch ($action)
        {
            case "edit":
                // Get article id
                $id = $this->_request->getParam('id');
                
                // Get user id
                $authId = $this->_auth->getIdentity()->getId();
                
                // Find article
                $article = $this->_em->find("App\Entity\Article", $id);
                
                // Check logged in user has permission to edit article
                if($article->getAuthor()->getId() != $authId && $this->_auth->getIdentity()->getRoleId() != \App\Acl::ADMIN)
                {
                    // User doesn't have access
                    $this->_redirect('/auth/forbidden');
                }
                
                break;

            default:
                break;
        }
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
                    $this->_redirect('/admin/news/');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/admin/news/');
                }
                
            }else{
                $addNewsForm->buildBootstrapErrorDecorators();
            }
        }

        $this->view->form = $addNewsForm;
    }
    
    public function editAction()
    {
        $id = $this->_request->getParam('id');
        $article = $this->_em->getRepository("\App\Entity\Article")->find($id);
     
        $editNewsForm = new EditNewsForm($article);
        
        if ($this->_request->isPost())
         {
            if($editNewsForm->isValid($this->_request->getPost())) 
            {
                // Get form data
                $data = $editNewsForm->getValues();
                
                // Get user posting
                $userId = $this->_auth->getIdentity()->getId();
                $user = $this->_em->find("App\Entity\User", $userId);
                
                // Modify article
                $article->setTitle($data['title']);
                $article->setSummary($data['summary']);
                $article->setContent($data['content']);
               
                // Save article
                try {
                    $this->_em->persist($article);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Published article successfully');
                    $this->_redirect('/admin/news/');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/admin/news/');
                }
                
            }else{
                $editNewsForm->buildBootstrapErrorDecorators();
            }
        }
        
        $this->view->articleTitle = $article->getTitle();
        $this->view->form = $editNewsForm;
    }
    
    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        
        $article = $this->_em->find("App\Entity\Article", $id);
        try{
            $this->_em->remove($article);
            $this->_em->flush();
        }catch(Exception $e){
            $this->_flashMessenger->addMessage('Error: '. $e);
        }
        
        $this->_redirect('/admin/news');
    }
    
    public function publishAction()
    {
        $id = $this->_request->getParam('id');
        
        $article = $this->_em->find("App\Entity\Article", $id);
        $article->publish(true);
        
        try{
            $this->_em->persist($article);
            $this->_em->flush();
        }catch(Exception $e){
            $this->_flashMessenger->addMessage('Error: '. $e);
        }
        
        $this->_redirect('/admin/news');
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