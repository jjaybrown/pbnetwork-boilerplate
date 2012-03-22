<?php
namespace App\Form\Community\Forums;

class AddForum extends \EasyBib_Form
{
    private $_categories;
    
    public function __construct($categories, $options = null)
    {
        $this->_categories = $categories;
        parent::__construct();
    }

    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/community/forum/add/'));
        $this->setAttrib('id', 'addForum');
        
        $category = new \Zend_Form_Element_Select('category');
        $category->setLabel("Category:")
                ->addMultiOptions($this->_categories);
        
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
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Add Forum");
        
        $this->addElements(array($category, $name, $description, $private, $submit));

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