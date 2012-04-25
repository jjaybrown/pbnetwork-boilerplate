<?php

use App\Controller as AppController;
use App\Entity\User as User;
use App\Entity\Profile as Profile;
use App\Form\Profile\Create as CreateForm;
use App\Entity\Interest as Interest;

class Site_ProfileController extends AppController
{
    
    private $_id;
    public function init()
    {
        parent::init();
        
        // Create test profile for current user
        $this->_id = $this->_auth->getIdentity()->getId();
        /*$dob = \DateTime::createFromFormat("d-m-Y", "05-08-1987");
        $profile = new Profile("Jason", "Brown", $dob);
        $user = $this->_em->find("\App\Entity\User", $this->_id);
        
        $user->setProfile($profile);
        $profile->setUser($user);
        
        $this->_em->persist($user);
        $this->_em->persist($profile);
        $this->_em->flush();
        
        \Zend_Debug::dump($profile);*/
    }
    
    
    public function viewAction()
    {
        // Check if we're viewing logged in users profile
        $id = $this->_request->getParam('id');
        if(!is_null($id))
        {
            $this->_id = $id;
        }
        
        $user = $this->_em->find("\App\Entity\User", $this->_id);
        
        if($user->getProfile() === null)
        {// No profile for this user available 
            
            // Check if logged in user is attempting to view their profile 
            if($this->_id == $this->_auth->getIdentity()->getId())
            {
                // Ask the user to create their profile
            }else{
                // Display no profile available page
            }
        }else{
            //\Zend_Debug::dump($user->getProfile());
            $this->view->user = $user;
        }
    }
    
    public function createAction()
    {
        $createForm = new CreateForm();
        $this->view->form = $createForm;
    }
    
    public function interestsAction()
    {
        $this->_helper->layout->disableLayout();
        $request = $this->getRequest();
        // Get interest from database
        //$interest = $this->_em->getRepository("App\Entity\Profile")->findBy('name' => $data['interest']);
        $data = $request->getPost();
        echo $data;
    }
}