<?php
namespace App\Repository\Community;

use Doctrine\ORM\EntityRepository;
 
class Forum extends EntityRepository
{
    public function forums()
    {
        $query = $this->_em->createQuery('SELECT f FROM App\Entity\Community\Forum f');
        $forums = $query->execute();
        \Zend_Debug::dump($forums);die;
    }
}
