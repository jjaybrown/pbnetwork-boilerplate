<?php

use App\Controller as AppController;

class Admin_OrderController extends AppController
{

    public function init()
    {
        parent::init();
    }
    
    public function indexAction()
    {
        // Get all orders
        $orders = $this->_em->getRepository("\App\Entity\Cart")->findAll();
        $this->view->orders = $orders;
    }
    
    public function auditAction()
    {
        
    }
}
?>
