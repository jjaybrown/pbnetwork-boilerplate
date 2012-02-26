<?php
namespace App\Form\Event;

class ViewEvent extends \EasyBib_Form
{
    protected $_event = null;

    public function __construct($event)
    {
        $this->_event = $event;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/event/index/view/id/'.$this->_event->getId()));
        $this->setAttrib('id', 'viewEvent');
        
        $quantity = new \Zend_Form_Element_Select('quantity');
        
        // Check we have enough tickets to display our max purchase amount
        $max_purchase_amount = \Zend_Registry::get('max_purchase_amount');
        if($this->_event->getNumTickets() >= $max_purchase_amount)
        {
            for($i = 1; $i <= $max_purchase_amount; $i++)
            {
                $quantity->addMultiOption($i, $i);
            }
        }else{
            for($i = 1; $i <= $this->_event->getNumTickets(); $i++)
            {
                $quantity->addMultiOption($i, $i);
            }
        }
        
        $submit = new \Zend_Form_Element_Button('submit');

        $submit->setLabel('Purchase');
        $this->addElements(array($quantity, $submit));

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

    public function __set($name, $value)
    {
        if ($value instanceof Zend_Form_Element) {
            $this->addElement($value, $name);
            return;
        } elseif ($value instanceof Zend_Form) {
            $this->addSubForm($value, $name);
            return;
        } elseif (is_array($value)) {
            $this->addDisplayGroup($value, $name);
            return;
        } elseif ($value instanceof App\Entity\Event){
            $this->_event = $value;
            return;
        }

        require_once 'Zend/Form/Exception.php';
        if (is_object($value)) {
            $type = get_class($value);
        } else {
            $type = gettype($value);
        }
        throw new Zend_Form_Exception('Only form elements and groups may be overloaded; variable of type "' . $type . '" provided');
    }
}