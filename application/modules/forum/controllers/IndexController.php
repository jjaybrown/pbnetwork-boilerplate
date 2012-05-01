<?php
use App\Controller as AppController;
use App\Entity\Community\Category as Category;
use App\Entity\Community\Forum as Forum;
use App\Form\Community\Forums\AddForum as AddForum;
use App\Form\Community\Forums\EditForum as EditForum;

class Forum_IndexController extends AppController
{
    
    private $_categories;
    
    public function init()
    {
        parent::init();
        $this->_categories = $this->_em->getRepository('App\Entity\Community\Category')->findAll();
    }
        
    public function indexAction()
    {
        // Check logged in user has a valid profile
        if($this->_auth->getIdentity())
        {
            $user = $this->_em->find("App\Entity\User", $this->_auth->getIdentity()->getId());
            if($user->getProfile() === null)
            {
                // Redirect to profile create page
                $this->_flashMessenger->addMessage(array('error' => 'You must create a profile to use the forums'));
                $this->_redirect('/profile/create');
            }
            
            /*
             *  Find latest post for each forum
             *  Need to cache this as it's heavy I/O
             */
            
            foreach($this->_categories as $cat)
            {
                foreach($cat->getForums() as $forum)
                {
                    $post = $this->_em->getRepository("App\Entity\Community\Forum")->latestPost($forum->getId());
                    if(is_array($post) && sizeof($post) > 0)
                        $forum->latestPost = $post[0];
                }
            }
            
            $this->view->categories = $this->_categories;
        }else{
            $this->_flashMessenger->addMessage(array('error' => 'An Error occurred, please check you are logged in'));
            $this->_redirect('/community');
        }
    }

    public function addAction()
    {
        // Set layout for admin
        $this->_helper->layout->setLayoutPath(APPLICATION_PATH.'/modules/admin/layouts/scripts/');
        $this->_helper->layout->setLayout('admin');
        
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
            $forumForm->setAction('/forum/index/add/cat/'.$id);
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
                    $this->_redirect('/forum/index/add');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage(array('error' => $e));
                    $this->_redirect('/forum/index/add');
                }
            }
        }
        
        $this->view->form = $forumForm;
    }
    
    public function editAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('id');
        
        if(!is_null($id))
        {
            $cats = array();
            foreach($this->_categories as $category)
            {
                $cats[] = $category->getName();
            }
            
            $forumForm = new EditForum($id, $cats);

            if ($this->_request->isPost()) {
                if ($forumForm->isValid($this->_request->getPost())) {

                    $data = $forumForm->getValues();
                    $this->_categories = $this->_categories[$data['_category']];
                    
                    $forum = $this->_em->find("\App\Entity\Community\Forum", $id);
                    $forum->setCategory($this->_categories);
                    $forum->setName($data['_name']);
                    $forum->setDescription($data['description']);
                    $forum->private = $data['private'];
                    //$forum = new Forum($this->_categories, $data['_name'], $data['_description'], $data['_private']);
                    $this->_categories->getForums()->add($forum);
                    try{
                        $this->_em->persist($forum);
                        $this->_em->flush();
                        $this->_flashMessenger->addMessage(array('success' => 'Successfully edited forum'));
                        $this->_redirect('/forum/index/edit/id/'.$id);
                    }
                    catch (Exception $e) {
                        // Alert user of error
                        $this->_flashMessenger->addMessage(array('error' => $e));
                        $this->_redirect('/forum/index/edit/id/'.$id);
                    }
                }
            }else{
                // Get user data from id
                $query = $this->_em->createQueryBuilder()
                    ->select('f')
                    ->from('App\Entity\Community\Forum', 'f')
                    ->where('f._id = ?1')
                    ->setParameter(1, $id)
                    ->getQuery();

                // Persist user object
                $object = $query->getResult();
                $this->_em->persist($object[0]);

                // Populate user as array
                $data = $query->getArrayResult();
                $data[0]["description"] = $data[0]["_description"];
                $forumForm->populate($data[0]);
            }

            $this->view->form = $forumForm;
        }
    }
    
    public function deleteAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('id');
        
        // Check id is valid
        if(!is_null($id))
        {
            $forum = $this->_em->find("\App\Entity\Community\Forum", $id);
            try{
                $this->_em->remove($forum);
                $this->_em->flush();
                $this->_flashMessenger->addMessage(array('success' => "Successfully deleted: ".$forum->getName()));
                $this->_redirect('/admin/community/forum/');
            }catch(Exception $e){
                // Display error message to user
                $this->_flashMessenger->addMessage(array('error' => $e));
                $this->_redirect('/admin/community/forum/');
            }
        }
    }
    
    public function lockAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('id');
        
        // Check id is valid
        if(!is_null($id))
        {
            $forum = $this->_em->find("\App\Entity\Community\Forum", $id);
            // Close forum (Lock)
            $forum->open = false;
            try{
                $this->_em->persist($forum);
                $this->_em->flush();
                $this->_flashMessenger->addMessage(array('success' => "Successfully locked: ".$forum->getName()));
                $this->_redirect('/admin/community/forum/');
            }catch(Exception $e){
                // Display error message to user
                $this->_flashMessenger->addMessage(array('error' => $e));
                $this->_redirect('/admin/community/forum/');
            }
        }
    }
    
    public function unlockAction()
    {
        // Get supplied forum id
        $id = $this->_request->getParam('id');
        
        // Check id is valid
        if(!is_null($id))
        {
            $forum = $this->_em->find("\App\Entity\Community\Forum", $id);
            // Open forum (unlock)
            $forum->open = true;
            try{
                $this->_em->persist($forum);
                $this->_em->flush();
                $this->_flashMessenger->addMessage(array('success' => "Successfully unlocked: ".$forum->getName()));
                $this->_redirect('/admin/community/forum/');
            }catch(Exception $e){
                // Display error message to user
                $this->_flashMessenger->addMessage(array('error' => $e));
                $this->_redirect('/admin/community/forum/');
            }
        }
    }
}