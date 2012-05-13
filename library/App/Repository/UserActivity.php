<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
 
class UserActivity extends EntityRepository
{
    public function findByInterval($id, $interval)
    {
        $interval = date('Y-m-d H:i:s', strtotime("-$interval minutes"));
        $stmt = "SELECT u FROM App\Entity\UserActivity u WHERE u._user = '$id' AND u._controller != 'auth' AND u._action !='logout' AND (u._when > '$interval' AND u._when < CURRENT_TIMESTAMP())";
        $result = $this->_em->createQuery($stmt)->getResult();
        
        return $result;
    }
    
    public function usersOnline($interval)
    {
        $interval = date('Y-m-d H:i:s', strtotime("-$interval minutes"));
        $stmt = "SELECT u FROM App\Entity\UserActivity u WHERE u._controller != 'auth' AND u._action !='logout' AND (u._when > '$interval' AND u._when < CURRENT_TIMESTAMP()) GROUP BY u._user";
        $result = $this->_em->createQuery($stmt)->getResult();
        
        return count($result);
    }
    
    public function pageViews($module, $controller, $action, $params = null)
    {
        $stmt = "SELECT v FROM App\Entity\UserActivity v WHERE v._module = '$module' AND v._controller = '$controller' AND v._action = '$action'";
        $result = $this->_em->createQuery($stmt)->getResult();
        
        return count($result);
    }
}
