<?php
namespace App\Form\Profile;

class Create extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAttrib('enctype', 'multipart/form-data');
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
        
        $picture = new \Zend_Form_Element_File('picture', array(
                'label' => 'Picture',
                'required' => true,
                'MaxFileSize' => 2097152, // 2097152 bytes = 2 megabytes
                'validators' => array(
                    array('Count', false, 1),
                    array('Size', false, 2097152),
                    array('Extension', false, 'gif,jpg,png'),
                    array('ImageSize', false, array('minwidth' => 100,
                                                    'minheight' => 100,
                                                    'maxwidth' => 1000,
                                                    'maxheight' => 1000))
                )
            ));
            
        $picture->setValueDisabled(true);
        $picture->setLabel('');
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Save Profile");
        $submit->setIgnore(true)
                ->setAttrib('class', 'pull-right btn-primary');
        
        $this->addElements(array($first, $last, $dob, $location, $interests, $bio, $picture, $submit));
        
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