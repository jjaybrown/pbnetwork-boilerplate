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
            if ('logout' != $this->getRequest()->getActionName()) {
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
        //$this->_helper->redirector('login');
        \Zend_Debug::dump($this->_auth->getIdentity());
    }
    
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_processLogin($form->getValues())) {
                    // We're authenticated!
                    //$this->_helper->redirector('index', 'index');
                }else{
                    // Auth failed
                    \Zend_Debug::dump("failed");
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
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
                \Zend_Debug::dump($request->getPost());
                // Process registration data
                $data = $request->getPost();
                $user = new User($data['username'], $data['password'], $data['email']);
                \Zend_Debug::dump($user);
            }
        }
        $this->view->form = $form;
    }
    
    protected function _processLogin($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter('Doctrine');
        $adapter->setIdentity($values['username']); 
        $adapter->setCredential($values['password']);

        $result = $this->_auth->authenticate($adapter);

        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            
            // Remove Array index from query
            if(isset($user[0])){
                $user = $user[0];
            }
            
            $this->_auth->getStorage()->write($user);
            return true;
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