<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Facebook")
 * @Table(name="facebook")
 */
class Facebook
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    /**
     * @Column(type="bigint", name="fid", unique="true")
     */
    private $_fid;
    
    /**
     * @Column(type="integer", name="uid")
     */
    
    private $_uid;
    
    
    public function __construct($fid, $uid)
    {
        $this->_fid = $fid;
        $this->_uid = $uid;
    }
    
    public function getFid()
    {
        return $this->_fid;
    }
    
    /**
     * Returns User Entity from uid
     * @return App\Entity\User 
     */
    public function getUserId()
    {
        return $this->_uid;
    }
    
}