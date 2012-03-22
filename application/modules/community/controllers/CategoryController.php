<?php
use App\Controller as AppController;
use App\Entity\Community\Category as Category;
use App\Form\Community\Forums\AddCategory as AddCategory;

class Community_CategoryController extends AppController
{
    
    private $_categories;
    
    public function init()
    {
        parent::init();
        
        $this->_categories = $this->_em->getRepository('App\Entity\Community\Category')->findAll();
    }
        
    public function indexAction()
    {
        $this->view->categories = $this->_categories;
    }
    
    public function addAction()
    {
        $catForm = new AddCategory();
        
        if ($this->_request->isPost()) {
            if ($catForm->isValid($this->_request->getPost())) {

                $data = $catForm->getValues();
                $category = new Category($data['name'], $data['description']);
                
                try{
                    $this->_em->persist($category);
                    $this->_em->flush();
                    $this->_flashMessenger->addMessage('Successfully created category');
                    $this->_redirect('/community/category/add');
                }
                catch (Exception $e) {
                    // Alert user of error
                    $this->_flashMessenger->addMessage('Error: '. $e);
                    $this->_redirect('/community/category/add');
                }
            }
        }
        
        $this->view->form = $catForm;
    }
}