<?php 

namespace App\Plugin\Form\Validators;

class UsernameUnique extends \Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';
 
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Username already exists'
    );
 
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
        
        // Check database table Users for an existing username
        $this->em = \Zend_Registry::get('em');
        $row = count($this->em->createQuery("SELECT u FROM App\Entity\User u WHERE u._username='".$value."'")->getResult());
        if($row == 0)
        {
            return true;
        }
        $this->_error(self::NOT_MATCH);
        return false;
    }
}
?>