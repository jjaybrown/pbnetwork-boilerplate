<?php

use App\Form\Auth\Login as Login;

class Site_AuthController extends Zend_Controller_Action
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
    }
        
    public function indexAction()
    {
        $form = new Login();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_process($form->getValues())) {
                    // We're authenticated!
                    //\Zend_Debug::dump($auth->hasIdentity());
                    //\Zend_Debug::dump($auth->getIdentity());
                    //$this->_helper->redirector('index', 'index');
                }else{
                    // Auth failed
                    \Zend_Debug::dump("failed");
                }
            }
        }
        $this->view->form = $form;
    }
    
    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter('Doctrine');
        $adapter->setIdentity($values['username']); 
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
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