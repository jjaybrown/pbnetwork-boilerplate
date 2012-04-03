<?php
namespace App\Form\Community\Forums;

class AddThread extends \EasyBib_Form
{
    private $_forums;
    
    public function __construct($forums, $options = null)
    {
        $this->_forums = $forums;
        parent::__construct();
    }

    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/forum/thread/add/'));
        $this->setAttrib('id', 'addThread');
        
        $forums = new \Zend_Form_Element_Select('forum');
        $forums->setLabel("Forums:")
                ->addMultiOptions($this->_forums);
        
        $name = new \Zend_Form_Element_Text('name');
        $name->setLabel("Name:")
             ->setRequired(true);
        
        $description = new \Zend_Form_Element_Text('description');
        $description->setLabel("Description:")
                    ->setRequired(true);
        
        $private = new \Zend_Form_Element_Select('private');
        $private->setlabel('Private:')
                ->addMultiOptions(array(
                    'false','true'
        ));
        
        $sticky = new \Zend_Form_Element_Select('sticky');
        $sticky->setlabel('Sticky:')
                ->addMultiOptions(array(
                    'false','true'
        ));
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Add Thread");
        
        $this->addElements(array($forums, $name, $description, $private, $sticky, $submit));

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