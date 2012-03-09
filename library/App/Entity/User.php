<?php
namespace App\Entity;

/**
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
    
    public function getId()
    {
        return $this->_id;
    }
}