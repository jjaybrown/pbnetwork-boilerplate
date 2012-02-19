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

    public function findUpcoming($limit = 10)
    {
        $qb = $this->createQueryBuilder("e");
        $qb->add("orderBy", "e._startDate ASC");
        $qb->setMaxResults($limit);
        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function findByEventStartMonth(\Zend_Date $startDate)
    {
        $month = $startDate->get('M');
        $year = $startDate->get('yyyy');
        $stmt = 'SELECT e FROM App\Entity\Event e WHERE SUBSTRING(e._startDate,6,2) = '.$month.' AND SUBSTRING(e._endDate,1,4) = '.$year.' ORDER BY e._startDate DESC';
        return $this->_em->createQuery($stmt)->getResult();
        
    }
    
    public function findByEventEndMonth(\Zend_Date $endDate)
    {
        $month = $endDate->get('M');
        $year = $endDate->get('yyyy');
        $stmt = 'SELECT e FROM App\Entity\Event e WHERE SUBSTRING(e._endDate,6,2) = '.$month.' AND SUBSTRING(e._endDate,1,4) = '.$year.' ORDER BY e._endDate DESC';
        return $this->_em->createQuery($stmt)->getResult();
        
    }
}
