<?php
use App\Controller as AppController;
use App\Entity\Community\Post as Post;
use App\Form\Community\Forums\AddPost as PostForum;

class Forum_PostController extends AppController
{
    private $_thread;
    private $_posts;
    private $_breadcrumbs;
    
    public function init()
    {
        parent::init();
        $id = $this->_request->getParam('thread');
        $this->_thread = $this->_em->find('App\Entity\Community\Thread', $id);
        $this->_posts = $this->_thread->getPosts();
    }
        
    public function indexAction()
    {
        $postForm = new PostForum($this->_thread);
        
        $this->_breadcrumbs = new Zend_Navigation();
        $this->_breadcrumbs->addPage(array(
            'module' => 'forum',
            'controller' => 'thread',
            'action' => 'view',
            'id' => $this->_thread->getForum()->getId(),
            'label' => $this->_thread->getForum()->getName()
        ));
        
        $this->_breadcrumbs->addPage(array(
            'module' => 'forum',
            'controller' => 'post',
            'action' => 'index',
            'id' => $this->_thread->getId(),
            'label' => $this->_thread->getName(),
            'active' => true
        ));
        
        $this->view->breadcrumbs = $this->_breadcrumbs;
        
        $this->view->thread = $this->_thread;
        $this->view->postForm = $postForm;
        
    }
    
    public function viewAction()
    {
        // Get forum id
        $id = $this->_request->getParam('id');
        
        $post = $this->_em->find('App\Entity\Community\Post' ,$id);
        
        $this->view->post = $post;
    }
    
    public function addAction()
    {
        $postForm = new PostForum($this->_thread);
        if ($this->_request->isPost()) {
            if ($postForm->isValid($this->_request->getPost())) {

                $data = $postForm->getValues();
                
                // Get User Entity for current logged in user
                $user = $this->_em->find("App\Entity\User", $this->_auth->getIdentity()->getId());
                
                // Create new Post object
                $post = new Post($user, $this->_thread, $data['post']);
                // Add Post to Thread
                $this->_thread->getPosts()->add($post);
                try{
                    $this->_em->persist($post);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage(array('success' => 'Successfully added post'));
                    $this->_redirect('/forum/post/index/thread/'.$this->_thread->getId());
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage(array('error' => $e));
                    $this->_redirect('/forum/post/index/thread/'.$this->_thread->getId());
                }
            }
        }
        
        $this->view->post = $postForm;
    }
    
    public function replyAction()
    {
        $id = $this->_request->getParam('id');
        
        $post = $this->_em->find("App\Entity\Community\Post", $id);
        
        // Get User Entity for current logged in user
        $user = $this->_em->find("App\Entity\User", $this->_auth->getIdentity()->getId());

        // Create new Post object
        $post = new Post($user, $this->_thread, "<pre>".$post->getContent()."</pre>");
        // Add Post to Thread
        $this->_thread->getPosts()->add($post);
        try{
            $this->_em->persist($post);
            $this->_em->flush();
            $this->_flashMessenger->addMessage(array('success' => 'Successfully added post'));
            $this->_redirect('/forum/post/index/thread/'.$this->_thread->getId());
        }
        catch (Exception $e) {
            // Alert user of error
            $this->_flashMessenger->addMessage(array('error' => $e));
            $this->_redirect('/forum/post/index/thread/'.$this->_thread->getId());
        }
        
    }
            
}