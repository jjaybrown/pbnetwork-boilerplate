<?php
use App\Controller as AppController;
use App\Entity\Image as Image;
use App\Form\Community\Groups\Create as CreateGroup;

class Community_GroupController extends AppController
{
   
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('group');
    }
        
    public function indexAction()
    {
        $form = new CreateGroup;
        
        $this->view->form = $form;
    }
    
    public function createAction()
    {
        $form = new CreateGroup;
        if($this->_request->isPost())
        {
            $image = new Image(Image::GROUP, $_FILES['uploaded_image']['tmp_name']);
            
            try{
                $this->_em->persist($image);
                $this->_em->flush();
                
                $this->_flashMessenger->addMessage(array('success' => 'Successfully created your group'));
                $this->_redirect('/community/group/');
            }catch(Exception $e){
                //@TODO Log error
                
                $this->_flashMessenger->addMessage(array('error' => 'There was an error creating this group, please try again.'));
                $this->_redirect('/community/group/');
            }
        }
        
        $this->view->form = $form;
    }
}