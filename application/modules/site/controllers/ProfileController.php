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
        parent::init(array('facebook' => true));
        
        // Get user id
        $this->_id = $this->_auth->getIdentity()->getId();
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
        $user = $this->_em->find("\App\Entity\User", $this->_id);
        
        if ($this->_request->isPost())
        {
            $data = $this->_request->getPost();
            
            if ($createForm->isValid($data))
            {
                $dob = \DateTime::createFromFormat("d-m-Y", $data['dob']);
                $profile = new Profile($data['first'], $data['last'], $dob);
                $profile->setUser($user);
                
                // Check if location is set
                if(!empty($data['location']))
                {
                    $profile->setlocation($data['location']);
                }
                
                // Check if bio is set
                if(!empty($data['bio']))
                {
                    $profile->setBio($data['bio']);
                }
                    
                try {
                    $this->_em->persist($profile);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage(array('success' => 'Successfully create your profile'));
                    $this->_helper->redirector('view', 'profile');
                }catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage(array('error' => 'Error: '. $e));
                    $this->_helper->redirector('create', 'profile');
                }
            }else{
                $createForm->buildBootstrapErrorDecorators();
            }
        }else{
            $picture = new \Zend_Form_Element_File('picture', array(
                'label' => 'Picture',
                'required' => true,
                'MaxFileSize' => 2097152, // 2097152 bytes = 2 megabytes
                'validators' => array(
                    array('Count', false, 1),
                    array('Size', false, 2097152),
                    array('Extension', false, 'gif,jpg,png'),
                    array('ImageSize', false, array('minwidth' => 100,
                                                    'minheight' => 100,
                                                    'maxwidth' => 1000,
                                                    'maxheight' => 1000))
                )
            ));
            
            $picture->setValueDisabled(true);
            $picture->setLabel('');
            $createForm->addElement($picture);
        }
        
        $this->view->gravitar = Profile::get_gravatar($user->getEmailAddress());
        $this->view->form = $createForm;
    }
    
    public function facebookAction()
    {
        $action = $this->_request->getParam('option');
        switch($action)
        {
            case "connect":
                // Connect Facebook profile
                $this->_connectFb();
                break;
            case "create":
                $this->_createUsingFb();
                break;
        }
 
        
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
    
    protected function _connectFb()
    {
        try{
            $user = $this->_facebook->getUser();
            if($user) // Check for logged in user
            {
                // Make API requet to Facebook
                $query = $this->_facebook->api('/me?fields=installed');
                
                // Check if user has installed the application
                if($query['installed'])
                {
                    $this->notification("Facebook connected", "Successfully connected your Facebook account");
                }
            }else{ // No users logged in 
                $this->_redirect($this->_facebook->getLoginUrl(array('scope' => 'user_about_me, user_birthday' ,'redirect_uri' => 'http://pbnetwork.dev/profile/facebook/option/create')));
            }
        }catch(\App\Classes\Facebook\FacebookApiException $e){
            $this->_flashMessenger->addMessage(array('error' => 'Error: '. $e));
            $this->_helper->redirector('index', 'index');
        }
    }
    
    protected function _createUsingFb()
    {
        try{
            $user = $this->_facebook->getUser();
            if($user) // Check for logged in user
            {
                // Make API requet to Facebook
                $query = $this->_facebook->api('/me?fields=first_name,last_name,gender,bio,birthday,email,location,picture,permissions&type=large');
                
                // Check if user has installed the application
                if($query['permissions']['data'][0]['installed'])
                {
                    $dob = \DateTime::createFromFormat("d/m/Y", $query['birthday']);
                    $profile = new Profile($query['first_name'], $query['last_name'], $dob);
                    $profile->setLocation($query['location']['name']);
                    $profile->setBio($query['bio']);
                    $profile->setPicture($query['picture']);
                    
                    // Get user
                    $user = $this->_em->find("\App\Entity\User", $this->_id);
                    $profile->setUser($user);
                
                    try {
                        $this->_em->persist($profile);
                        $this->_em->flush();
                        $this->_flashMessenger->addMessage(array('success' => 'Successfully create your profile'));
                        $this->_helper->redirector('view', 'profile');
                    }catch (Exception $e) {
                        // Alert user of error
                        $this->_flashMessenger->addMessage(array('error' => 'Error: '. $e));
                        $this->_helper->redirector('create', 'profile');
                    }
                }
            }else{ // No users logged in 
                $this->_redirect($this->_facebook->getLoginUrl(array('scope' => 'user_about_me, user_birthday', 'redirect_uri' => 'http://pbnetwork.dev/profile/facebook')));
            }
        }catch(\App\Classes\Facebook\FacebookApiException $e){
            $this->_flashMessenger->addMessage(array('error' => 'Error: '. $e));
            $this->_helper->redirector('index', 'index');
        }
    }
}