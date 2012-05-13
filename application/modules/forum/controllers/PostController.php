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
        
        // Log thread views
        $this->_thread->views = ($this->_thread->views + 1);
        $this->_em->persist($this->_thread);
        $this->_em->flush();
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
        
        $this->view->forum = "<a href='/forum/thread/view/id/".$this->_thread->getForum()->getId()."'>".ucwords($this->_thread->getForum()->getName())."</a>";
        $this->view->thread = "<a href='/forum/post/index/id/".$this->_thread->getForum()->getId()."/thread/".$this->_thread->getId()."'/>".ucwords($this->_thread->getName())."</a>";
        
        // Get most recent posts
        $posts = $this->_em->getRepository('App\Entity\Community\Thread')->recentPosts($this->_thread->getId());
        $this->view->posts = $posts;
        $this->view->post = $postForm;
    }
    
    public function replyAction()
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
        
        
        // Set content of editor to be the post we are replying to
        $id = $this->_request->getParam('id');
        
        $post = $this->_em->find("App\Entity\Community\Post", $id);
        
        $postForm->getElement('post')->setValue("<pre>".$post->getContent()."</pre>");
        
        $this->view->forum = "<a href='/forum/thread/view/id/".$this->_thread->getForum()->getId()."'>".ucwords($this->_thread->getForum()->getName())."</a>";
        $this->view->thread = "<a href='/forum/post/index/id/".$this->_thread->getForum()->getId()."/thread/".$this->_thread->getId()."'/>".ucwords($this->_thread->getName())."</a>";
        
        // Get most recent posts
        $posts = $this->_em->getRepository('App\Entity\Community\Thread')->recentPosts($this->_thread->getId());
        $this->view->posts = $posts;
        $this->view->post = $postForm;
        
        // Set view to add post
        $this->_helper->viewRenderer->setRender('add'); 
    }
    
    /*public function replyAction()
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
        
    }*/
            
}