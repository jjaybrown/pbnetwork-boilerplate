<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
 
class User extends EntityRepository
{
    public function activation($code)
    {
        $stmt = "SELECT u FROM App\Entity\User u WHERE u._activationCode = '".$code."' AND u._active = false";
        $result = $this->_em->createQuery($stmt)->getResult();
        if(count($result) > 0)
        {
            $result[0]->setActiveStatus();
            try{
                $this->_em->persist($result[0]);
                $this->_em->flush();
                
                return $result[0];
            }catch(Exception $e){
                // Log error
            }
        }
        return false;
    }
    
    public function validUsername($username)
    {
        $stmt = "SELECT u FROM App\Entity\User u WHERE u._username = '".$username."'";
        $result = $this->_em->createQuery($stmt)->getResult();
        if(count($result) == 0)
        {
            // Check username isn't on the reserved list
            if(!in_array($username, \Zend_Registry::get('reserved_usernames')))
                return true;
        }
        return false;
    }
}
