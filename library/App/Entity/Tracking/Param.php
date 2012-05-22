<?php

/**
 * Holds key/value pairs of param within requests 
 */

namespace App\Entity\Tracking;

/**
 * @Entity(repositoryClass="App\Repository\Tracking\Param")
 * @Table(name="user_activity_param")
 */
class Param
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\Tracking\UserActivity")
     * @JoinColumns({
     *  @JoinColumn(name="activity_id", referencedColumnName="id")
     * })
     */
    private $_activity;
    /** @Column(type="string", name="name") */
    private $_key;
    /** @Column(type="string", name="value") */
    private $_value;
    
    public function __construct($activity, $key, $value)
    {
        $this->_activity = $activity;
        $this->_key = $key;
        $this->_value = $value;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getKey()
    {
        return $this->_key;
    }
    
    public function getValue()
    {
        return $this->_value;
    }
}