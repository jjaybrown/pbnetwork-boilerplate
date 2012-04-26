<?php
namespace App\Form\Auth;

class Login extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/auth/login'));
        $this->setAttrib('id', 'login');       
        
        $username = new \Zend_Form_Element_Text('username');
        $username->addFilters(array('StringTrim', 'StringToLower'))
                //->addValidator(array('StringLength', false, array(0, 50)))
                ->setRequired(true)
                ->setLabel("Username:");
        
        $password = new \Zend_Form_Element_Password('password');
        $password->addFilters(array('StringTrim', 'StringToLower'))
                //->addValidator(array('StringLength', false, array(0, 50)))
                ->setRequired(true)
                ->setLabel("Password:");
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Login")
                ->setAttrib('class', 'btn-inverse');
        
        // add CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
        $this->addElements(array($username, $password, $submit));
        
        // Setup decorators for form elements
        \EasyBib_Form_Decorator::setFormDecorator(
            $this, \EasyBib_Form_Decorator::BOOTSTRAP, 'submit'
        );
    }

    public function isValid($data)
    {
        if (!is_array($data)) {
            require_once 'Zend/Form/Exception.php';
            throw new \Zend_Form_Exception(__METHOD__ . ' expects an array');
        }
        return parent::isValid($data);
    }
}