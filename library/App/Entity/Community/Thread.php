<?php
namespace App\Entity\Community;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Community\Thread")
 * @Table(name="threads")
 */
class Thread
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $_id;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\Community\Forum")
     * @JoinColumns({
     *  @JoinColumn(name="forum_id", referencedColumnName="id")
     * })
     */
    
    private $_forum;
    
    /** @Column(type="string", name="name") */
    private $_name;
    /** @Column(type="string", name="description") */
    private $_description;
    /** @Column(type="datetime", name="created", nullable="true") */
    private $_created;
    /** @Column(type="datetime", name="updated", nullable="true") */
    private $_updated;
    /** @Column(type="boolean", name="private") */
    public $private = false;
    /** @Column(type="boolean", name="locked") */
    public $locked = false;
    /** @Column(type="boolean", name="sticky") */
    public $sticky = false;
    
    public function __construct($forum, $name, $description, $private = false, $locked = false, $sticky = false)
    {
        $this->setForum($forum);
        $this->_posts = new ArrayCollection();
        $this->_name = $name;
        $this->_description = $description;
        $this->_created = new \DateTime;
        $this->_updated = new \DateTime;
        $this->private = $private;
        $this->locked = $locked;
        $this->sticky = $sticky;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getForum()
    {
        return $this->_forum;
    }
    
    public function setForum($forum)
    {
        $this->_forum = $forum;
        $this->_updated = new \DateTime;
        return $this;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        $this->_updated = new \DateTime;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setDescription($desc)
    {
        $this->_description = $desc;
        $this->_updated = new \DateTime;
        return $this;
    }
    
    public function getCreated($format = "d-m-Y H:iA")
    {
        return $this->_created->format($format);
    }
    
    public function getUpdated($format = "d-m-Y H:iA")
    {
        return $this->_updated->format($format);
    }
    
    public function __toString()
    {
        return "Thread: ".$this->getId();
    }
}