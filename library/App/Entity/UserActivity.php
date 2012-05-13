<?php
namespace App\Entity;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\UserActivity")
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
    /** @Column(type="array", name="params") */
    private $_params;
    /** @Column(type="datetime", name="created") */
    private $_when;
    
    /**
     * Creates a user activity record
     * @param \App\Entity\User $user
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function __construct($user, \Zend_Controller_Request_Abstract $request)
    {
        $this->_user = $user;
        $this->_action = $request->getActionName();
        $this->_controller = $request->getControllerName();
        $this->_module = $request->getModuleName();
        $this->_params = $this->filterParams($request->getUserParams());
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
    public static function filterParams($params)
    {
        foreach($params as $key => $value)
        {
            switch($key)
            {
                case "action":
                case "controller":
                case "module":
                    unset($params[$key]);
                    break;
            }
        }
        
        return $params;
    }
}