<?php
namespace App\Form\Event;

class AddEvent extends \EasyBib_Form
{
    public function init()
    {
        \ZendX_JQuery::enableForm($this);
        // $this->setDefaultTranslator(\Zend_Registry::get('Zend_Translate')); ???
        $this->setMethod('POST');
        $this->setAction($this->getView()->baseUrl('/event/index/add'));
        $this->setAttrib('id', 'addEvent');
        $name = new \Zend_Form_Element_Text('name');
        $start = new \ZendX_JQuery_Form_Element_DatePicker('start', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $end = new \ZendX_JQuery_Form_Element_DatePicker('end', array('jQueryParams' => array('dateFormat' => 'dd-mm-yy')));
        $location = new \ZendX_JQuery_Form_Element_AutoComplete(
                "location", array('label' => 'Autocomplete:')
        );
        $location->setJQueryParams(array('source' => \Zend_Registry::get('locations')));
        $venue = new \Zend_Form_Element_Text('venue');
        $description = new \Zend_Form_Element_Textarea('description');
        $tickets = new \Zend_Form_Element_Text('tickets');
        $price = new \Zend_Form_Element_Text('price',array('value' => 0));
        $submit = new \Zend_Form_Element_Button('submit');

        $name->setLabel('Event name:')
            ->setRequired(true);

        $start->setLabel('Start Date:')
              ->setRequired(true);

        $end->setLabel('End Date:')
            ->setRequired(true);

        $location->setLabel('Location:')
            ->setRequired(true);

        $venue->setLabel('Venue:')
            ->setRequired(true);

        $description->setLabel('Event Info:')
            ->setRequired(true)
            ->setAttrib('rows', '5')
            ->setTranslator(\Zend_Registry::get('Zend_Translate'));

        $tickets->setLabel('Ticket Allocation:')
                ->setRequired(true);

        $price->setLabel('Ticket Price:')
                ->setRequired(false)
                ->setDescription('If event is free, leave price as 0 (ZERO)');

        $submit->setLabel('Create event');
        $this->addElements(array($name, $start, $end, $location, $venue, $description, $tickets, $price, $submit));

        // Setup decorators for form elements
        \EasyBib_Form_Decorator::setFormDecorator(
            $this, \EasyBib_Form_Decorator::BOOTSTRAP, 'submit'
        );

        // Setup decorators for jQuery elements
        $start->setDecorators(array('FormElements'
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

        $end->setDecorators(array('FormElements'
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

        $location->setDecorators(array('FormElements'
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