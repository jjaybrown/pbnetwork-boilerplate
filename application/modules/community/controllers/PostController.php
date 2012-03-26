<?php
use App\Controller as AppController;
use App\Entity\Community\Post as Post;
use App\Form\Community\Forums\AddPost as PostForum;

class Community_PostController extends AppController
{
    private $_thread;
    private $_posts;
    
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
                /*$this->_em->merge($this->_auth->getIdentity());
                $this->_em->persist($this->_auth->getIdentity());*/
                $post = new Post($this->_auth->getIdentity(), $this->_thread, $data['post']);
                $this->_thread->getPosts()->add($post);
                try{
                    $this->_em->persist($post);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage(array('success' => 'Successfully added post'));
                    $this->_redirect('/community/post/index/thread/'.$this->_thread->getId());
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage(array('error' => $e));
                    $this->_redirect('/community/post/index/thread/'.$this->_thread->getId());
                }
            }
        }
        
        $this->view->post = $postForm;
    }
    
            
}