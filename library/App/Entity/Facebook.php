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
     */
    private $_id;
    
    /**
     * @Id @Column(type="integer", name="uid")
     */
    
    private $_uid;
    
    
    public function __construct($id, $uid)
    {
        $this->_id = $id;
        $this->_uid = $uid;
    }
    
    public function getId()
    {
        return $this->_id;
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