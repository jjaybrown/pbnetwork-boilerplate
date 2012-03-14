<?php

namespace App;

class Acl extends \Zend_Acl
{
    /**
     * Define xml config for building Acl
     * @var string 
     */
    const XML = 'XML';
    
    /**
     * Define database config for building Acl
     * @var string 
     */
    const DB = 'DB';
    
    protected $_configType;
    
    protected $_config;
    
    public function __construct($configType = null, \Zend_Config $config = null)
    {
        $this->_configType = $configType;
        $this->_config = $config;
        
        switch($this->_configType)
        {
            case 'XML':
                // Build Acl from Xml
                $this->_buildAclFromXml();
                break;
            
            case 'DB':
                // Build Acl from database
                break;
        }
    }
    
    /**
     * Build the ACL
     *
     * @return void
     */
    protected function _buildAclFromXml()
    {
        if (!isset($this->_config->resources->resource)) {
            throw new \Zend_Acl_Exception('No resources have been defined.');
        }
        
        // Add the resources
        
        // Check theres more than one resource available
        if(count($this->_config->resources) > 1)
        {
            foreach ($this->_config->resources->resource as $resource) {
                if (!$this->has($resource)) {
                    $this->addResource(new \Zend_Acl_Resource($resource));
                }
            }
        }else{ // Only one resource available
            if (!$this->has($this->_config->resources->resource)) {
                    $this->addResource(new \Zend_Acl_Resource($this->_config->resources->resource));
                }
        }
        
        $roles = array($this->getCurrentRole());

        /*if ($roles[0] == self::AUTH_ROLE) {
            $roles[] = self::AUTH_INACTIVE_MEMBER_ROLE;
        }*/

        foreach ($roles as $role) {
            if (!isset($this->_config->roles->$role)) {
                throw new \Zend_Acl_Exception("The role '" . $role . "' has not been defined.");
            } else {
                if (!$this->hasRole($role)) {
                    $this->addRole($role);
                }
                
                // set a global deny for this role
                $this->deny($role);
                if (isset($this->_config->roles->{$role}->allow)) {
                    $allow = $this->_config->roles->{$role}->allow;
                    
                    
                    // always use an array of resources, even if there's only 1
                    if ($allow->resource instanceof \Zend_Config) {
                        $resources = $allow->resource->toArray();
                    } else {
                        $resources = array($allow->resource);
                    }

                    foreach ($resources as $resource) {
                        if ($resource === '*') {
                            $this->allow($role); // global allow
                        } elseif ($resource && $this->has($resource)) {
                            $this->allow($role, $resource);
                        }
                    }
                }
            }
        }
    }
    
    public function getCurrentRole()
    {
        // Get Auth Object
        $auth = \Zend_Auth::getInstance();
        
        // Check if there is a logged in user
        if($auth->hasIdentity())
        {
            $user = $auth->getIdentity();
            return $user->getRoleId();
        }else{
            // No one logged in assume guest
            return "Guest";
        }
    }
}
?>
