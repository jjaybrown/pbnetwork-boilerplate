<?php
namespace App\Form\Community\Groups;

class Create extends \EasyBib_Form
{
    
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('id', 'group');
        $this->setAction('/community/group//index/create');
        
        $name = new \Zend_Form_Element_Text('name');
        $name->setLabel("Name:")
                    ->setRequired(true);
        $description = new \Zend_Form_Element_Text('description');
        $description->setLabel("Description:")
                    ->setRequired(true);
        $image = new \Zend_Form_Element_File('image');
        
        $submit = new \Zend_Form_Element_Submit('submit');
        $submit->setLabel("Create")
                ->setAttrib('class', 'btn btn-warning pull-right');
        $this->addElements(array($name, $description, $image, $submit));

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