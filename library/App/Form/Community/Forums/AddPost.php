<?php
namespace App\Form\Community\Forums;

class AddPost extends \EasyBib_Form
{
    private $_thread;
    
    public function __construct($thread, $options = null)
    {
        $this->_thread = $thread;
        parent::__construct();
    }

    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/community/post/add/thread/'.$this->_thread->getId()));
        $this->setAttrib('id', 'addThread');
        
        
        
        $post = new \Zend_Form_Element_Textarea('post');
        $post->setRequired(true)
                ->setAttrib("style", "width:100%;");
        
        $submit = new \Zend_Form_Element_Button('submit');
        $submit->setLabel("Post");
        
        $this->addElements(array($post, $submit));

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