<?php
use App\Controller as AppController;
use App\Entity\Community\Forum as Forum;
use App\Entity\Community\Thread as Thread;
use App\Entity\Community\Category as Category;
use App\Form\Community\Forums\AddCategory as AddCategory;
use App\Form\Community\Forums\AddForum as AddForum;
use App\Form\Community\Forums\AddThread as AddThread;

class Community_ForumController extends AppController
{
    
    private $_categories;
    private $_forums;
    
    public function init()
    {
        parent::init();
        
        $this->_categories = $this->_em->getRepository('App\Entity\Community\Category')->findAll();
        $this->_forums = $this->_em->getRepository('App\Entity\Community\Forum')->findAll();
        // Did we get any forums from the database?
        /*if(count($this->_forums) == 0)
        { // Nope, create forum
            $this->_forums = new Forum("Test", "Test forum"); 
            $thread = new Thread($this->_forums, "welcome", "welcome to the forum");
            $this->_forums->getThreads()->add($thread);
            $this->_em->persist($this->_forums);
            $this->_em->flush();

            $this->_forums = $this->_em->getRepository('App\Entity\Community\Forum')->findAll();
        }*/
    }
        
    public function indexAction()
    {
        $this->view->categories = $this->_categories;
    }
    
    public function addcategoryAction()
    {
        $catForm = new AddCategory();
        
        if ($this->_request->isPost()) {
            if ($catForm->isValid($this->_request->getPost())) {

                $data = $catForm->getValues();
                $category = new Category($data['name'], $data['description']);
                
                try{
                    $this->_em->persist($category);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Successfully created category');
                    $this->_redirect('/community/forum/addCategory');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/community/forum/addCategory');
                }
            }
        }
        
        $this->view->form = $catForm;
    }
    
    public function addforumAction()
    {
        // Get supplied category id
        $id = $this->_request->getParam('cat');
        // Get all categories if no id given
        if(is_null($id))
        {
            // Loop throuh array of entities and store name of category for select
            $cats = array();
            foreach($this->_categories as $category)
            {
                $cats[] = $category->getName();
            }
            
            $forumForm = new AddForum($cats);
        }else{
            // Get category using supplied id
            $this->_categories = $this->_em->find("\App\Entity\Community\Category", $id);
            
            // Create array for select box in form
            $cats[] = $this->_categories->getName();
            
            // Wrap result in array so we don't break code further along
            $this->_categories = array($this->_categories);
            
            // Init form
            $forumForm = new AddForum($cats);
            
            // Set action to include category specific id
            $forumForm->setAction('/community/forum/addForum/cat/'.$id);
        }
        
        if ($this->_request->isPost()) {
            if ($forumForm->isValid($this->_request->getPost())) {

                $data = $forumForm->getValues();
                $this->_categories = $this->_categories[$data['category']];
                $forum = new Forum($this->_categories, $data['name'], $data['description'], $data['private']);
                $this->_categories->getForums()->add($forum);
                try{
                    $this->_em->persist($forum);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Successfully created forum');
                    $this->_redirect('/community/forum/addForum');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/community/forum/addForum');
                }
            }
        }
        
        $this->view->form = $forumForm;
    }
    
    public function addthreadAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('forum');
        // Get all categories if no id given
        if(is_null($id))
        {
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
            $threadForm->setAction('/community/forum/addThread/forum/'.$id);
        }
        
        if ($this->_request->isPost()) {
            if ($threadForm->isValid($this->_request->getPost())) {

                $data = $threadForm->getValues();
                $this->_forums = $this->_forums[$data['forum']];
                $thread = new thread($this->_forums, $data['name'], $data['description'], $data['private']);
                $this->_forums->getThreads()->add($thread);
                try{
                    $this->_em->persist($thread);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Successfully created thread');
                    $this->_redirect('/community/forum/addThread');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/community/forum/addThread');
                }
            }
        }
        
        $this->view->form = $threadForm;
    }
    
            
}