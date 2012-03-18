<?php
namespace App\Form\Admin\User;
use App\Acl as Acl;

class Access extends \EasyBib_Form
{
    private $_permissons = array(Acl::MEMBER => Acl::MEMBER, Acl::ADMIN => Acl::ADMIN);
    
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/admin/user/permissions/id/'));
        $this->setAttrib('id', 'permissions');       
      
        $permissions = new \Zend_Form_Element_Select('_roleId');
        $permissions->addMultiOptions($this->_permissons)
                ->setLabel('Access Level:');
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Save Permissions");
        
        $this->addElements(array($permissions, $submit));
        
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