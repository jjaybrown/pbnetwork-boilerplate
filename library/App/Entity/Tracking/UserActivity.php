<?php
namespace App\Entity\Tracking;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Tracking\UserActivity")
 * @Table(name="user_activity")
 */
class UserActivity
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumns({
     *  @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    
    private $_user;
    
    /** @Column(type="string", name="action") */
    private $_action;
    /** @Column(type="string", name="controller") */
    private $_controller;
    /** @Column(type="string", name="module") */
    private $_module;
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $_params
     * 
     * @OneToMany(targetEntity="App\Entity\Tracking\Param", mappedBy="_activity", cascade={"persist", "remove"})
     */
    private $_params;
    
    /** @Column(type="datetime", name="created") */
    private $_when;
    
    /**
     * Creates a user activity record
     * @param \App\Entity\User $user
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function __construct($user, $module, $controller, $action, $params)
    {
        $this->_user = $user;
        $this->_module = $module;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_params = new ArrayCollection();
        
        // Add params to user activity 
        $this->_filterParams($params);
        $this->_when = new \DateTime;
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
    public function getActionName()
    {
        return $this->_action;
    }
    
    public function getControllerName()
    {
        return $this->_controller;
    }
    
    public function getModuleName()
    {
        return $this->_module;
    }
    
    /*
     * @return array params
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    public function getFormattedParams()
    {
        $str = "/";
        
        foreach($this->_params as $key => $value)
        {
            $str .= $key."/".$value;
        }
        
        return $str;
    }
    
    public function when($format = 'H:i:s d-m-Y')
    {
        return $this->_when->format($format);
    }
    
    /**
     * Removes action, controller and model
     * @param mixed $params
     * @return mixed 
     */
    protected function _filterParams($params)
    {
        //\Zend_Debug::dump($params);die;
        foreach($params as $key => $value)
        {
            switch($key)
            {
                case "action":
                case "controller":
                case "module":
                    unset($params[$key]);
                    break;
                default:
                    // Add param to params ArrayCollection
                    $param = new \App\Entity\Tracking\Param($this, $key, $value);
                    $this->_params->add($param);
                    break;
            }
        }
        
        return $params;
    }
}