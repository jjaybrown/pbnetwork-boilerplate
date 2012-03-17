<?php 

namespace App\Plugin\Form\Validators;

class UsernameNotRestricted extends \Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';
 
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Invalid username, uses restricted word'
    );
 
    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
        
        // Check username is not reserved i.e. admin related
        if(!in_array($value, \Zend_Registry::get('reserved_usernames')))
        {
                return true;
        }
        
        $this->_error(self::NOT_MATCH);
        return false;
    }
}
?>