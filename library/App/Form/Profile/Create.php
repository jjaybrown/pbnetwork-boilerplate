<?php
namespace App\Form\Profile;

class Create extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/profile/create'));
        $this->setAttrib('id', 'profile');       

        $first = new \Zend_Form_Element_Text('first');
        $first->setAttrib('placeholder', 'your firstname*')
              ->setRequired(true);
        
        $last = new \Zend_Form_Element_Text('last');
        $last->setRequired(true)
              ->setAttrib('placeholder', 'your lastname*');
        
        $dob = new \ZendX_JQuery_Form_Element_DatePicker('dob', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $dob->setAttrib('placeholder', 'your date of birth*')
              ->setRequired(true);
        
        $location = new \Zend_Form_Element_Text('location');
        $location->setRequired(false)
              ->setAttrib('placeholder', 'your location');
        
        $interests = new \Zend_Form_Element_Text('interests');
        $interests->setRequired(false)
              ->setAttrib('placeholder', 'your interests');
        
        $bio = new \Zend_Form_Element_TextArea('bio');
        $bio->setRequired(false)
            ->setAttribs(array('class' => 'span3', 'rows' => '5', 'cols' => '10'))
            ->setAttrib('placeholder', 'little about you');
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Save Profile");
        $submit->setIgnore(true)
                ->setAttrib('class', 'pull-right btn-primary');
        
        $this->addElements(array($first, $last, $dob, $location, $interests, $bio, $submit));
        
        // Setup decorators for form elements
        \EasyBib_Form_Decorator::setFormDecorator(
            $this, \EasyBib_Form_Decorator::BOOTSTRAP, 'submit'
        );
        
        $dob->setDecorators(array('FormElements'
            => 'UiWidgetElement',
            array('BootstrapErrors'),
            array('Description',
                array('tag' => 'span', 'class' => 'help-inline')
            ),
            array('BootstrapTag', array('class' => 'input')),
            array('Label'),
            array('HtmlTag',
                array('tag' => 'div', 'class' => 'control-group')
            )
         ));
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