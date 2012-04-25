<?php

use App\Controller as AppController;
use App\Form\Auth\Login as LoginForm;
use App\Form\Auth\Register as RegisterForm;
use App\Entity\User as User;
use App\Classes\Auth\User as AuthUser;

class Site_AuthController extends AppController
{
    public function init()
    {
        parent::init();
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
                if ($this->_processLogin($form->getValues())) {
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
                $user = new User($data['username'], $data['password'], $data['email']);
                $this->_em->persist($user);
                $this->_em->flush();
                
                // Clear users from cache
                $this->_cache->delete('users');
                // Retrieve activation code and email user for activation
                $code = $user->getActivationCode();
                //@TODO add messages to flash, send email with activation code
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
    
    protected function _processLogin($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter('Doctrine');
        $adapter->setIdentity($values['username']); 
        // Salt password
        $salt = \Zend_Registry::get('salt');
        $adapter->setCredential(SHA1($salt.$values['password']));

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
                            ->setCredentialField('_password')
                            //->setCredentialTreatment('SHA1(?)')
                            ->setIdentity('username')
                            ->setCredential('password');
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
}