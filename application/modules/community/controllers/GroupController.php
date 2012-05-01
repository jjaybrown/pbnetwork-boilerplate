<?php
use App\Controller as AppController;

class Community_GroupController extends AppController
{

    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('group');
    }
        
    public function indexAction()
    {
        
    }
    
    public function createAction()
    {
    }
}