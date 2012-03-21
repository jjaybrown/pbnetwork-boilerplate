<?php

use App\Controller as AppController;

class Admin_CommunityController extends AppController
{

    private $_forums;
    
    public function init()
    {
        parent::init();
    }
    
    public function indexAction()
    {
        
    }
    
    public function forumAction()
    {
        $this->_forums = $this->_em->getRepository("\App\Entity\Community\Forum")->findAll();
        $this->view->forums = $this->_forums;
    }
}





