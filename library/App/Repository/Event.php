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
    
    public function findByEventStartDate(\Zend_Date $startDate)
    {
        $month = $startDate->get('M');
        $year = $startDate->get('yyyy');
        $stmt = 'SELECT e FROM App\Entity\Event e WHERE MONTH(e.start) = '.$month.' AND YEAR(e.start) = '.$year.' ORDER BY e.id DESC';
        return $this->_em->createQuery($stmt)->getResult();
    }
}
