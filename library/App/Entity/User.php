<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\User")
 * @Table(name="user")
 */
class User
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="string", name="username", unique="true") */
    private $_username;
    /** @Column(type="string", name="password") */
    private $_password;
    /** @Column(type="string", name="email_address") */
    private $_emailAddress;
    /** @Column(type="datetime", name="created")*/
    private $_created;
    /** @Column(type="boolean", name="activate")*/
    private $_activate;
    /** @Column(type="string", name="activation_code") */
    private $_activationCode;
    
    public function __construct()
    {
        $this->_username = "";
        $this->_password = "";
        $this->_emailAddress = "";
        $this->_created = new \DateTime();
        $this->_activate = false;
        $this->_activationCode = "";
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getUsername()
    {
        return $this->_username();
    }
    
    public function getPassword()
    {
        return $this->_password;
    }
    
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }
}