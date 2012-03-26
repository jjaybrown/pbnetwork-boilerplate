<?php
use App\Controller as AppController;
use App\Entity\Community\Category as Category;
use App\Form\Community\Forums\AddForum as AddForum;

class Community_ForumController extends AppController
{
    
    private $_categories;
    
    public function init()
    {
        parent::init();
        $this->_categories = $this->_em->getRepository('App\Entity\Community\Category')->findAll();
    }
        
    public function indexAction()
    {
        $this->view->categories = $this->_categories;
    }

    public function addAction()
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
            $forumForm->setAction('/community/forum/add/cat/'.$id);
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
                    $this->_flashMessenger->addMessage(array('success' => 'Successfully created forum'));
                    $this->_redirect('/community/forum/add');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage(array('error' => $e));
                    $this->_redirect('/community/forum/add');
                }
            }
        }
        
        $this->view->form = $forumForm;
    }         
}