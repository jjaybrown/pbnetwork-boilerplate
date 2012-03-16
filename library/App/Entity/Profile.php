<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Profile")
 * @Table(name="profiles")
 */
class Profile
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="string", name="first_name") */
    private $_firstName;
    /** @Column(type="string", name="last_name") */
    private $_lastName;
    /** @Column(type="datetime", name="dob") */
    private $_dob;
    
    public function __construct()
    {
    }
    
    public function getId()
    {
        return $this->_id;
    }
}