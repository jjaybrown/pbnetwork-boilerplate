<?php
namespace App\Form\News;

class Edit extends \EasyBib_Form
{
    private $_article;
    
    public function __construct($article, $options = null)
    {
        $this->_article = $article;
        parent::__construct($options);
    }
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/news/index/edit/id/'.$this->_article->getId()));
        $this->setAttrib('id', 'editNews');
        $title = new \Zend_Form_Element_Text('title');
        $title->setLabel("Title")
                ->setRequired(true)
                ->setAttribs(array('class' => 'span6'))
                ->setValue($this->_article->getTitle());
        
        $summary = new \Zend_Form_Element_Text('summary');
        $summary->setLabel("Summary")
                ->setRequired(true)
                ->setAttribs(array('class' => 'span6'))
                ->setValue($this->_article->getSummary());
        
        $embargo = new \ZendX_JQuery_Form_Element_DatePicker('start', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $embargo->setLabel('Embargo:')
              ->setRequired(false)
              ->setDescription("The date this article should be displayed");
        
        $content = new \Zend_Form_Element_Textarea('content');
        $content->setRequired(true)
                ->setValue($this->_article->getContent());
        
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