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
    
    public function count()
    {
        $query = $this->_em->createQuery("SELECT COUNT(t) FROM App\Entity\Community\Thread t");
        return $query->getSingleResult();
    }
    
    public function findAllBySticky($forum_id)
    {
        $query = $this->_em->createQuery("SELECT t FROM App\Entity\Community\Thread t WHERE t._forum = '$forum_id' ORDER BY t.sticky DESC");
        return $query->getResult();
    }
}
