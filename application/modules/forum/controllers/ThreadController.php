<?php
use App\Controller as AppController;
use App\Entity\Community\Thread as Thread;
use App\Form\Community\Forums\AddThread as AddThread;

class Forum_ThreadController extends AppController
{
    public function init()
    {
        parent::init();      
    }
    
    public function viewAction()
    {
        // Get forum id
        $id = $this->_request->getParam('id');
        $forum = $this->_em->find("App\Entity\Community\Forum", $id);
        $threads = $this->_em->getRepository("App\Entity\Community\Thread")->findAllBySticky($id);
        
        $breadcrumbs = new Zend_Navigation();
        $breadcrumbs->addPage(array(
            'module' => 'forum',
            'controller' => 'thread',
            'action' => 'view',
            'id' => $this->_request->getParam('id'),
            'label' => $forum->getName()
        ));
        
        $this->view->breadcrumbs = $breadcrumbs;
        $this->view->forum = $forum;
        $this->view->threads = $threads;
        
        $this->view->paginator = \Zend_Paginator::factory($threads);
    }
    
    public function addAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('forum');
        // Get all categories if no id given
        if(is_null($id))
        {
            $this->_forums = $this->_em->getRepository('App\Entity\Community\Forum')->findAll();  
            
            // Loop throuh array of entities and store name of forum for select
            $forums = array();
            foreach($this->_forums as $forum)
            {
                $forums[] = $forum->getName();
            }
            
            $threadForm = new AddThread($forums);
        }else{
            // Get category using supplied id
            $this->_forums = $this->_em->find("\App\Entity\Community\Forum", $id);
            
            // Create array for select box in form
            $forums[] = $this->_forums->getName();
            
            // Wrap result in array so we don't break code further along
            $this->_forums = array($this->_forums);
            
            // Init form
            $threadForm = new AddThread($forums);
            
            // Set action to include category specific id
            $threadForm->setAction('/forum/thread/add/forum/'.$id);
        }
        
        if ($this->_request->isPost()) {
            if ($threadForm->isValid($this->_request->getPost())) {

                $data = $threadForm->getValues();
                $this->_forums = $this->_forums[$data['forum']];
                $thread = new Thread($this->_forums, $data['name'], $data['description'], $data['private']);
                $this->_forums->getThreads()->add($thread);
                try{
                    $this->_em->persist($thread);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Successfully created thread');
                    $this->_redirect('/forum/thread/view/id/'.$id);
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/forum/thread/add');
                }
            }
        }
        
        $this->view->form = $threadForm;
    }
    
            
}