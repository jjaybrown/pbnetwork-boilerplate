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
    /** @Column(type="string", name="picture") */
    private $_pictureUrl;
    /** @Column(type="string", name="bio") */
    private $_bio;
    /** @Column(type="string", name="location") */
    private $_location;
    
    /**
     * @OneToOne(targetEntity="\App\Entity\User", inversedBy="_profile", cascade={"persist", "remove"})
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    
    private $_user;
    
    public function __construct($first, $last, \Datetime $dob)
    {
        $this->_firstName = $first;
        $this->_lastName = $last;
        $this->_dob = $dob;
        $this->_pictureUrl = "img/profiles/default.jpg";
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getFirstName()
    {
        return $this->_firstName;
    }
    
    public function setFirstName($first)
    {
        $this->_firstName = $first;
        return $this;
    }
    
    public function getLastName()
    {
        return $this->_lastName;
    }
    
    public function setLastName($last)
    {
        $this->_lastName = $last;
        return $this;
    }
    
    public function getFullName()
    {
        return ucwords($this->_firstName." ".$this->_lastName);
    }
    
    public function getDob()
    {
        return $this->_dob->format("d-M-Y");
    }
    
    public function setDob(\Datetime $date)
    {
        $this->_dob = $date;
        return $this;
    }
    
    public function getPicture()
    {
        return $this->_pictureUrl;
    }
    
    public function setPicture($url)
    {
        return $this->_pictureUrl;
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }
    
    public function getLocation()
    {
        return $this->_location;
    }
    
    public function setLocation($location)
    {
        $this->_location = $location;
        return $this;
    }
}