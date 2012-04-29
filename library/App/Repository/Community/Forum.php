<?php
namespace App\Repository\Community;

use Doctrine\ORM\EntityRepository;
 
class Forum extends EntityRepository
{
    /**
     * Gets the latest post within the specified forum
     * @param int $id Forum id
     * @return array result of query
     */
    public function latestPost($id)
    {
        $query = $this->_em->createQuery("SELECT p FROM App\Entity\Community\Post p LEFT JOIN p._thread t LEFT JOIN t._forum f WHERE f._id ='".$id."'  ORDER BY p._created DESC");
        $query->setMaxResults(1);
        return $query->getResult();
    }
}
