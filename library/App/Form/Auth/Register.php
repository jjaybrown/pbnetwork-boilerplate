<?php
namespace App\Form\Auth;
use App\Plugin\Form\Validators\PasswordConfirm as PasswordConfirm;

class Register extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/auth/register'));
        $this->setAttrib('id', 'login');       
        
        $username = new \Zend_Form_Element_Text('username');
        //@TODO add filter to check for unique username
        $username->addFilters(array('StringTrim', 'StringToLower'))
                //->addValidator(array('StringLength', false, array(0, 50)))
                ->setRequired(true)
                ->setLabel("Username:")
                ->setDescription("This will be publically visible");
        
        $password = new \Zend_Form_Element_Password('password');
        $password->setRequired(true)
                 
                ->addValidator(new PasswordConfirm())
                 ->setLabel("Password:");
        
        $passwordConfirm = new \Zend_Form_Element_Password('passwordConfirm');
        $passwordConfirm->setRequired(true)
                        ->setLabel("Confirm password:");
        
        $email = new \Zend_Form_Element_Text('email');
        $email->setRequired(true)
              ->addValidators(array('EmailAddress'))
              ->setLabel("Email Address:");
        
        $emailConfirm = new \Zend_Form_Element_Text('emailConfirm');
        $emailConfirm->setRequired(true)
              ->addValidators(array('EmailAddress'))
              ->setLabel("Confirm Email:");
        
        
        
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Register");
        
        // add CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
        $this->addElements(array($username, $password, $passwordConfirm, $email, $emailConfirm, $submit));
        
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