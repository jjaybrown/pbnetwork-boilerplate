<?php
namespace App\Form\Admin\User;
use App\Plugin\Form\Validators\UsernameUnique as UsernameUnique;

class Edit extends \EasyBib_Form
{
    private $_id = null;
    
    public function __construct($id)
    {
        $this->_id = $id;
        parent::__construct();
    }
    
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/admin/user/edit/id/'.$this->_id));
        $this->setAttrib('id', 'edit');       
       
        $username = new \Zend_Form_Element_Text('_username');
        //@TODO add filter to check for unique username
        $username->addFilters(array('StringTrim', 'StringToLower'))
                ->setRequired(true)
                ->setLabel("Username:")
                ->setDescription("This will be publically visible");
        
        $email = new \Zend_Form_Element_Text('_emailAddress');
        $email->setRequired(true)
              ->addValidators(array('EmailAddress'))
              ->setLabel("Email Address:");
        
        $active = new \Zend_Form_Element_Select('_active');
        $active->addMultiOptions(array('0' => 'inactive', '1' => 'active'))
                ->setLabel('Account Status:');
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Save User");
        
        $this->addElements(array($username, $email, $active, $submit));
        
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
?>