<?php
namespace App\Form\News;

class Add extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/news/index/add'));
        $this->setAttrib('id', 'addNews');
        $title = new \Zend_Form_Element_Text('title');
        $title->setLabel("Title")
                ->setRequired(true)
                ->setAttribs(array('class' => 'span6'));
        
        $summary = new \Zend_Form_Element_Text('summary');
        $summary->setLabel("Summary")
                ->setRequired(true)
                ->setAttribs(array('class' => 'span6'));
        
        $embargo = new \ZendX_JQuery_Form_Element_DatePicker('start', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $embargo->setLabel('Embargo:')
              ->setRequired(false)
              ->setDescription("The date this article should be displayed");
        
        $content = new \Zend_Form_Element_Textarea('content');
        $content->setRequired(true);
        
        /*$submit = new \Zend_Form_Element_Submit('submit');
        $submit->setLabel("Publish Article");
        
        $save = new \Zend_Form_Element_Button('cancel');
        $save->setLabel("Save Draft")
                ->setAttrib("class", "btn btn-info");*/
        
        $this->addElements(array($title, $summary, $embargo, $content));

        // Setup decorators for form elements
        \EasyBib_Form_Decorator::setFormDecorator(
            $this, \EasyBib_Form_Decorator::BOOTSTRAP, 'submit'
        );
        
        // Setup decorators for jQuery elements
        $embargo->setDecorators(array('FormElements'
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