<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
 
class Article extends EntityRepository
{
    public function published()
    {
        $stmt = 'SELECT a FROM App\Entity\Article a WHERE a._published = true ORDER BY a._posted DESC';
            return $this->_em->createQuery($stmt)->getResult();
    }
}
