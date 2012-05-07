<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Article")
 * @Table(name="article")
 */
class Article
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="string", name="title") */
    private $_title;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumns({
     *  @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $_author;
    /** @Column(type="string", name="summary") */
    private $_summary;
    /** @Column(type="text", name="content") */
    private $_content;
    /** @Column(type="datetime", name="embargo")*/
    private $_embargo;
    /** @Column(type="datetime", name="created")*/
    private $_created;
    /** @Column(type="datetime", name="updated")*/
    private $_updated;
    /** @Column(type="datetime", name="posted", nullable="true")*/
    private $_posted;
    /** @Column(type="boolean", name="published")*/
    private $_published = false;
    
    public function __construct($title, $user, $summary, $content, \DateTime $embargo = null)
    {
        $this->_title = $title;
        $this->_author = $user;
        $this->_summary = $summary;
        $this->_content = $content;
        $this->_embargo = new \DateTime();
        $this->_created = new \DateTime();
        $this->_updated = new \DateTime();
    }
    
    public function getId()
    {
        return $this->_id;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
        $this->_updated = new \DateTime();
        return $this;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setAuthor($author)
    {
        $this->_author = $author;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getSummary()
    {
        return $this->_summary;
    }
    
    public function setSummary($summary)
    {
        $this->_summary = $summary;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getContent()
    {
        return $this->_content;
    }
    
    public function setContent($content)
    {
        $this->_content = $content;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getEmbargo($format = "D-M-Y H:i:s")
    {
        return $this->_embargo->format($format);
    }
    
    public function setEmbargo(\DateTime $date)
    {
        $this->_embargo = $date;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getCreated($format = "D-M-Y H:i:s")
    {
        return $this->_created->format($format);
    }
    
    public function setCreated(\DateTime $date)
    {
        $this->_created = $date;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function getUpdated($format = "D-M-Y H:i:s")
    {
        return $this->_updated->format($format);
    }
    
    public function getPosted($format = "D-M-Y H:i:s")
    {
        return $this->_posted->format($format);
    }
    
    public function setPosted(\DateTime $date)
    {
        $this->_posted = $date;
        $this->_updated = new \DateTime();
        return $this;
    }
    
    public function isPublished()
    {
        return $this->_published;
    }
    
    public function hasBeenEdited()
    {
        if($this->_updated > $this->_posted)
        {
            return true;
        }
        
        return false;
    }
    
    public function publish($ignoreEmbargo = false)
    {
        if($ignoreEmbargo)
        {
            // We want to post this straight away, override any existing embargo
            $this->_embargo = new \DateTime();
        }
        
        $this->_published = true;
        $this->setPosted(new \DateTime);
    }
}