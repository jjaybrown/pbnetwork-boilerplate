<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\User")
 * @Table(name="users")
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
    /** @Column(type="string", name="role") */
    private $_roleId = "Member";
    
    public function __construct($username, $password, $emailAddress)
    {
        $this->_username = $username;
        $this->_password = $password;
        $this->_emailAddress = $emailAddress;
        $this->_created = new \DateTime();
        $this->_activate = false;
        $this->_activationCode = $this->_generateActivationCode();
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
    
    public function _generateActivationCode()
    {
        // Take date object, salt and username and generate a hash
        return md5($this->_created->getTimestamp()."salt".$this->_username);
    }
}