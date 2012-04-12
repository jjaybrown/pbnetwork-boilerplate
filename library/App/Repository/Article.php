<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
 
class Article extends EntityRepository
{
    public function published($limit = 10)
    {
        $stmt = 'SELECT a FROM App\Entity\Article a WHERE a._published = true ORDER BY a._posted DESC';
            return $this->_em->createQuery($stmt)->getResult();
    }
    
    public function recentByAuthor($id)
    {
        $stmt = 'SELECT a FROM App\Entity\Article a WHERE a._author = '.$id.' ORDER BY a._posted DESC';
            return $this->_em->createQuery($stmt)->getResult();
    }
}
