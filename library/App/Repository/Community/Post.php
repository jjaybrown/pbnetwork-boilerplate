<?php
namespace App\Repository\Community;

use Doctrine\ORM\EntityRepository;
 
class Post extends EntityRepository
{
    public function recentActivity()
    {
        $stmt = 'SELECT p FROM App\Entity\Community\Post p GROUP BY p._thread ORDER BY p._updated DESC';
        return $this->_em->createQuery($stmt)->getResult();
    }
}
