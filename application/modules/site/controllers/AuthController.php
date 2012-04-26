<?php

use App\Controller as AppController;
use App\Form\Auth\Login as LoginForm;
use App\Form\Auth\Register as RegisterForm;
use App\Entity\User as User;
use App\Classes\Auth\User as AuthUser;
use App\Entity\Facebook as FacebookJoin;

class Site_AuthController extends AppController
{
    public function init()
    {
        // Init parent controll with facebook api access
        parent::init(array('facebook' => true));
    }
    
    public function preDispatch()
    {
        if ($this->_auth->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName() && 'forbidden' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            // If they aren't, they can't logout, so that action should 
            // redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index');
            }
        }
    }
    
    public function indexAction()
    {
        $this->_helper->redirector('login');
    }
    
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();
 
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_processLogin($form->getValues(), 'Doctrine')) {
                    // We're authenticated!
                    /*if($this->_auth->getIdentity()->getRoleId() == App\Acl::ADMIN)
                    {
                        
                        $this->_helper->redirector('index', 'admin');
                    }*/
                    
                    // Get referrer back from session
                    $session = new \Zend_Session_Namespace('tmp');
                    $this->_redirect($session->redirect);
                }else{
                    // Auth failed
                    $this->_flashMessenger->addMessage(array('error' => 'Login failed. Please check your username and password'));
                    $this->_helper->redirector('login');
                }
            }
        }elseif($this->getRequest()->getParam('facebook')){
            // Using facebook to login
            $uid = $this->_facebook->getUser();
            if($uid) // Got a user
            {
                if($this->_processLogin($uid, 'Facebook'))
                {
                    
                    // Get referrer back from session
                    $session = new \Zend_Session_Namespace('tmp');
                    $this->_redirect($session->redirect);
                }else{
                    // Auth failed
                    $this->_flashMessenger->addMessage(array('error' => 'Login failed. You\'ve not registered a Facebook account'));
                    $this->_helper->redirector('login');
                }
            }else{
                $this->_redirect($this->_facebook->getLoginUrl(
                        array(
                            'scope' => 'user_about_me, user_birthday' ,
                            'redirect_uri' => 'http://pbnetwork.dev/auth/login/facebook/true'
                            )
                        ));
            }
        }
        
        // Store referrer in session
        $session = new \Zend_Session_Namespace('tmp');
        $session->redirect = $request->getRequestUri();
        $this->view->form = $form;
        
        // Add registration form to login page
        /*$registerForm = new RegisterForm();
        $this->view->register = $registerForm;*/
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_flashMessenger->addMessage(array('info' => 'Successfully logged out'));
        $this->_helper->redirector('index'); // back to login page
    }
    
    public function forbiddenAction()
    {
        //@TODO Log unauthorized access attempt
    }
    
    public function registerAction()
    {
        $form = new RegisterForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {                
                // Process registration data
                $data = $request->getPost();
                
                try{
                    $user = new User($data['username'], $data['password'], $data['email']);
                    $this->_em->persist($user);
                    $this->_em->flush();

                    // Clear users from cache
                    $this->_cache->delete('users');
                    // Retrieve activation code and email user for activation
                    $code = $user->getActivationCode();
                    //@TODO add messages to flash, send email with activation code
                }catch(Exception $e){
                    // Something went wrong
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                    $this->_helper->redirector('register', 'auth');
                }
            }
        }elseif($this->getRequest()->getParam('facebook')){
            // Using facebook to login
            $uid = $this->_facebook->getUser();
            if($uid) // Got a user
            {
                $query = $this->_facebook->api('/me?fields=username,first_name,last_name,gender,bio,birthday,email');

                // Check fb user has a username
                if(!isset($query['username']) && is_null($this->getRequest()->getParam('username')))
                {
                    // Need to ask them for a username
                    $this->_helper->redirector('username', 'auth');
                }elseif(!isset($query['username'])){
                    $query['username'] = $this->getRequest()->getParam('username');
                }
                
                //\Zend_Debug::dump($query);die;
                try{
                    // Create user using creditionals
                    $user = new User($query['username'], md5(date('iMY')), $query['email']);
                    //$user->setActiveStatus(true);
                    $this->_em->persist($user);
                    $this->_em->flush();

                    // Create join table between facebook id and user id
                    $fbJoin = new FacebookJoin($uid, $user->getId());
                    $this->_em->persist($fbJoin);
                    
                    $this->_em->flush();

                    // Clear users from cache
                    $this->_cache->delete('users');
                    
                    // Create profile 
                    $this->createFacebookProfile($user);
                    
                    $this->_flashMessenger->addMessage(array('success' => 'Successfully created your Account, you can now login'));
                    $this->_helper->redirector('login', 'auth');
                    
                    // Log the user straight in
                    /*$this->_redirect($this->_facebook->getLoginUrl(
                        array(
                            'scope' => 'user_about_me, user_birthday' ,
                            'redirect_uri' => 'http://pbnetwork.dev/auth/login/facebook/true'
                            )
                        ));*/
                }catch(Exception $e){
                    // Something went wrong
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                    $this->_helper->redirector('register', 'auth');
                }
            }else{
                $this->_redirect($this->_facebook->getLoginUrl(
                        array(
                            'scope' => 'user_about_me, user_birthday, email' ,
                            'redirect_uri' => 'http://pbnetwork.dev/auth/register/facebook/true'
                            )
                        ));
            }
        }
        $this->view->form = $form;
    }
    
    public function activateAction()
    {
        // Check if form has been posted
        if(!is_null($this->_request->getParam('code')))
        {
            $activation = $this->_em->getRepository('App\Entity\User')->activation($this->_request->getParam('code'));
            
            if($activation)
            {
                // Redirect to profile setup and guide
                $this->_helper->redirector('index', 'index');
            }else{
                // Something went wrong
                $this->_flashMessenger->addMessage(array('error' => 'Sorry, we were unable to activated your account'));
                $this->_helper->redirector('activate', 'auth');
            }
        }
    }
    
    protected function _processLogin($values, $adp)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter($adp);
        
        // Setup adapter depending on it's type
        switch($adp)
        {
            case 'Doctrine':
                $adapter->setIdentity($values['username']); 
                // Salt password
                $salt = \Zend_Registry::get('salt');
                $adapter->setCredential(SHA1($salt.$values['password']));
                break;
            case 'Facebook':
                $adapter->setIdentity($values);
                break;
        }
        
        
        $result = $this->_auth->authenticate($adapter);

        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            
            // Remove Array index from query
            if(isset($user[0])){
                $user = $user[0];
            }
            
            // Check user has activated account
            if(!$user->isActive())
            {
                // User isn't activated remove user from auth
                $this->_auth->clearIdentity();
                return false;
            }else{
                // Create Auth User instance for storing to session
                $authUser = $this->_createAuthUser($user);
                // Write Auth User to session
                $this->_auth->getStorage()->write($authUser);
                return true;
            }
        }
        return false;
    }
    
    protected function _getAuthAdapter($adapter)
    {
        switch($adapter)
        {
            case "Doctrine":
                $authAdapter = new App_Auth_Adapter_Doctrine(
                    $this->_em
                );
                
                $authAdapter->setEntityName('App\Entity\User')
                            ->setIdentityField('_username')
                            ->setCredentialField('_password');
                break;
            case "Facebook":
                $authAdapter = new App_Auth_Adapter_Facebook(
                    $this->_em
                );
                
                $authAdapter->setEntityName('App\Entity\Facebook')
                            ->setIdentityField('_id');
                break;
        }
        
        return $authAdapter;
    }
    
    /**
     * Creates a Session Writeable version of User
     * Due to problem with session when storing User Entity
     * @param type $user The user entity to extract values from
     */
    protected function _createAuthUser($user)
    {
        return new AuthUser($user->getId(), $user->getUsername(), $user->getRoleId());
    }
    
    /**
     * Create Facebook profile 
     */
    
    protected function createFacebookProfile($user)
    {
        // Make API request to Facebook
        $query = $this->_facebook->api('/me?fields=first_name,last_name,gender,bio,birthday,email,location,picture,permissions&type=large');
        //\Zend_Debug::dump($query);
        // Check if user has installed the application
        if($query['permissions']['data'][0]['installed'])
        {
            $dob = \DateTime::createFromFormat("d/m/Y", $query['birthday']);
            $profile = new App\Entity\Profile($query['first_name'], $query['last_name'], $dob);
            // Check user has a location
            if(isset($query['location']))
            {
                $profile->setLocation($query['location']['name']);
            }else{
                $profile->setLocation("");
            }
            $profile->setBio($query['bio']);
            $profile->setPicture($query['picture']);
            $profile->setUser($user);

            try {
                $this->_em->persist($profile);
                $this->_em->flush();
                return true;
            }catch (Exception $e) {
                die($e->getMessage());
            }
        }
        
        return false;
    }
}