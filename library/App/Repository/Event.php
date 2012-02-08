<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
 
class Event extends EntityRepository
{
    public function findThemAll()
    {
        $stmt = 'SELECT e FROM App\Entity\Event e ORDER BY e._id DESC';
        return $this->_em->createQuery($stmt)->getResult();
    }
}
