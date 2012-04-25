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
        $first->setRequired(true)
              ->setAttrib('placeholder', 'your firstname');
        
        $last = new \Zend_Form_Element_Text('last');
        $last->setRequired(true)
              ->setAttrib('placeholder', 'your lastname');
        
        $dob = new \ZendX_JQuery_Form_Element_DatePicker('dob', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $dob->setAttrib('placeholder', 'your date of birth')
              ->setRequired(true);
        
        $location = new \Zend_Form_Element_Text('location');
        $location->setRequired(true)
              ->setAttrib('placeholder', 'your location');
        
        $interests = new \Zend_Form_Element_Text('interests');
        $interests->setRequired(true)
              ->setAttrib('placeholder', 'your interests');
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Save Profile");
        
        // add CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
        $this->addElements(array($first, $last, $dob, $location, $interests, $submit));
        
        // Setup decorators for form elements
        \EasyBib_Form_Decorator::setFormDecorator(
            $this, \EasyBib_Form_Decorator::BOOTSTRAP, 'submit'
        );
        
        // Setup decorators for jQuery elements
        $dob->setDecorators(array('FormElements'
            => 'UiWidgetElement',
            array('BootstrapErrors'),
            array('Description',
                array('tag' => 'span', 'class' => 'help-inline')
            ),
            array('BootstrapTag', array('class' => 'input')),
            array('Label'),
            array('HtmlTag',
                array('tag' => 'div', 'class' => 'clearfix')
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