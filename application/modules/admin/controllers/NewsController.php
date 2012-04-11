<?php

use App\Controller as AppController;

class Admin_NewsController extends AppController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $articles = $this->_em->getRepository("App\Entity\Article")->findAll();
        $this->view->articles = $articles;
    }
}





