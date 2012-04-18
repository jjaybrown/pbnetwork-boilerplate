<?php
namespace App\Entity;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

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
    /** @Column(type="datetime", name="updated")*/
    private $_updated;
    /** @Column(type="boolean", name="active")*/
    private $_active = false;
    /** @Column(type="string", name="activation_code") */
    private $_activationCode;
    /** @Column(type="string", name="role") */
    private $_roleId = "Member";
    
    private $_salt;
    
    /**
     * @OneToOne(targetEntity="\App\Entity\Profile", mappedBy="_user", cascade={"persist", "remove"})
     */
    
    private $_profile;
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $_posts
     * 
     * @OneToMany(targetEntity="App\Entity\Community\Post", mappedBy="_user", cascade={"persist", "remove"})
     */
    private $_posts;
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $_articles
     * 
     * @OneToMany(targetEntity="App\Entity\Article", mappedBy="_author", cascade={"persist", "remove"})
     */
    private $_articles;
    
    public function __construct($username, $password, $emailAddress)
    {
        $this->_username = $username;
        $this->_posts = new ArrayCollection();
        $this->_articles = new ArrayCollection();
        // Treat password with salt
        $this->_salt = \Zend_Registry::get('salt'); 
        $this->_password = SHA1($this->_salt.$password);
        $this->_emailAddress = $emailAddress;
        $this->_created = new \DateTime();
        $this->_updated = new \DateTime();
        $this->_activationCode = $this->_generateActivationCode();
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getUsername()
    {
        return $this->_username;
    }
    
    public function setUsername($username)
    {
        $this->_username = $username;
        // Update timestamp
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getPassword()
    {
        return $this->_password;
    }
    
    public function setPassword($password)
    {
        $this->_password = SHA1($this->_salt.$password);
        // Update timestamp
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }
    
    public function setEmailAddress($email)
    {
        $this->_emailAddress = $email;
        // Update timestamp
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getCreated($format = "d-m-Y H:i:s")
    {
        return $this->_created->format($format);
    }
    
    public function getUpdated($format = "d-m-Y H:i:s")
    {
        return $this->_updated->format($format);
    }
    
    public function getRoleId()
    {
        return $this->_roleId;
    }
    
    public function setRoleId($roleId)
    {
        $this->_roleId = $roleId;
        // Update timestamp
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getActivationCode()
    {
        return $this->_activationCode;
    }
    
    public function _generateActivationCode()
    {
        // Take date object, username and email address and generate a hash
        return md5($this->_created->getTimestamp().$this->_username.$this->_emailAddress);
    }
    
    public function isActive()
    {
        return $this->_active;
    }
    
    public function setActiveStatus($active = true)
    {
        $this->_active = $active;
        // Update timestamp
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getProfile()
    {
        return $this->_profile;
    }
    
    public function setProfile($profile)
    {
        $this->_profile = $profile;
        return $this;
    }
    
    public function getPosts()
    {
        return $this->_posts;
    }
    
    public function getPostCount()
    {
        return count($this->_posts);
    }
    
    public function getArticles()
    {
        return $this->_articles;
    }
}