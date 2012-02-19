<?php

class Event_IndexController extends Zend_Controller_Action
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;
    /**
     * @var Zend_Controller_Action_Helper
     */
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_em = Zend_Registry::get('em');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        // Get 5 upcoming events
        $events = $this->_em->getRepository("\App\Entity\Event")->findUpcoming();
        Zend_Debug::dump($events);
    }

    public function addAction()
    {
        // Create form
        $addEventForm = new \App\Form\Event\AddEvent();
        
        if ($this->_request->isPost()) {
            if ($addEventForm->isValid($this->_request->getPost())) {

                $data = $addEventForm->getValues();
                $newEvent = new \App\Entity\Event($data['name'], $data['start'], $data['end'], $data['location'], 50);
                $newEvent->setVenue($data['venue'])
                         ->setDescription($data['description'])
                         ->setNumTickets($data['tickets']);
                // Check if event is not free
                if($data['price'] != 0 && strtoupper($data['price']) != "ZERO"){
                    $newEvent->setPrice($data['price']);
                }

                try {
                    $this->_em->persist($newEvent);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Saved event successfully');
                    $this->_redirect('/event/');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/event/');
                }
            } else {
                $addEventForm->buildBootstrapErrorDecorators();
            }
        }

        $this->view->form = $addEventForm;
    }

    public function deleteAction()
    {
        // Request params
        $request = $this->getRequest();
        $id = $request->getParam('id');

        // Check an id has been provided
        if(isset($id)){
            // Get Entity by supplied id
            $event = $this->_em->getRepository("\App\Entity\Event")->find($id);
            try {
                $this->_em->remove($event);
                $this->_em->flush($event);
                $this->_flashMessenger->addMessage('Event was deleted successfully');
                $this->_redirect("/event/");
            }catch (Exception $e) {
                // Alert user of error
                $this->_flashMessenger->addMessage('Error: '. $e);
                $this->_redirect('/event/');
            }
        }else{
            $this->_flashMessenger->addMessage('Invalid event id');
        }
        $this->_redirect("/event/");
    }

    public function buildAction()
    {
        $events = $this->_em->getRepository("\App\Entity\Event")->findUpcoming();
        foreach($events as $event){
            $this->_indexEvent($event);
        }

        $this->render(index);
    }

    private function _indexEvent(\App\Entity\Event $event)
    {
       $client = Zend_Registry::get('es');
       $index = $client->getIndex('events');
       //$index->create(array(), true);
       $type = $index->getType('event');

       $doc = new Elastica_Document(
           $event->getId(), array('id' => $event->getId(),
           'name' => $event->getName(),
           'description' => $event->getDescription(),
           'location' => $event->getLocation(),
           'venue' => $event->getVenue())
       );
       // var_dump($doc);exit;
       $type->addDocument($doc);
       $index->refresh();
    }

}