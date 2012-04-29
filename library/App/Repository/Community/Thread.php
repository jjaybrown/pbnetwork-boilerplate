<?php
namespace App\Repository\Community;

use Doctrine\ORM\EntityRepository;
 
class Thread extends EntityRepository
{
    public function recentPosts($id, $limit = 5)
    {
        $query = $this->_em->createQuery("SELECT p FROM App\Entity\Community\Post p JOIN p._thread t WHERE t._id = '".$id."' ORDER BY p._updated DESC");
        $query->setMaxResults($limit);
        return $query->getResult();

    }
}
