<?php
namespace App\Entity\Community;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Community\Post")
 * @Table(name="posts")
 */
class Post
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $_id;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\Community\Thread")
     * @JoinColumns({
     *  @JoinColumn(name="thread_id", referencedColumnName="id")
     * })
     */
    
    private $_thread;
    
    /** @Column(type="string", name="content") */
    private $_content;
    /** @Column(type="datetime", name="created", nullable="true") */
    private $_created;
    /** @Column(type="datetime", name="updated", nullable="true") */
    private $_updated;
    
    public function __construct($thread, $content)
    {
        $this->setThread($thread);
        $this->_content = $content;
        $this->_created = new \DateTime;
        $this->_updated = new \DateTime;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getContent()
    {
        return $this->_content;
    }
    
    public function getThread()
    {
        return $this->_thread;
    }
    
    public function setThread($thread)
    {
        $this->_thread = $thread;
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
        return "Post: ".$this->getId();
    }
}