<?php
namespace App\Form\Community\Forums;

class EditForum extends \EasyBib_Form
{
    private $_id;
    private $_categories;
    
    public function __construct($id, $categories, $options = null)
    {
        $this->_id = $id;
        $this->_categories = $categories;
        parent::__construct();
    }

    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/community/forum/edit/id/'.$this->_id));
        $this->setAttrib('id', 'editForum');
        
        $category = new \Zend_Form_Element_Select('_category');
        $category->setLabel("Category:")
                ->addMultiOptions($this->_categories);
        
        $name = new \Zend_Form_Element_Text('_name');
        $name->setLabel("Name:")
             ->setRequired(true);
        
        $description = new \Zend_Form_Element_Text('description');
        $description->setLabel("Description:")
                    ->setRequired(true);
        
        $private = new \Zend_Form_Element_Select('private');
        $private->setlabel('Private:')
                ->addMultiOptions(array(
                    0 => 'false', 1 => 'true'
        ));
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Edit Forum");
        
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