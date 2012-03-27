<?php

use App\Controller as AppController;
use App\Form\Auth\Login as LoginForm;
use App\Form\Auth\Register as RegisterForm;
use App\Entity\User as User;

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
                    if($this->_auth->getIdentity()->getRoleId() == App\Acl::ADMIN)
                    {
                        $this->_helper->redirector('index', 'admin');
                    }else{
                        $this->_helper->redirector('index', 'index');
                    }
                }else{
                    // Auth failed
                    $this->_flashMessenger->addMessage(array('error' => 'Login failed. Please check your username and password'));
                    $this->_helper->redirector('login');
                }
            }
        }
        $this->view->form = $form;
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
                $this->_auth->getStorage()->write($user);
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
}