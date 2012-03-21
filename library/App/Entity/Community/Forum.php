<?php
namespace App\Entity\Community;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Community\Forum")
 * @Table(name="forum")
 */
class Forum
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $_id;
    /** @Column(type="string", name="name") */
    private $_name;
    /** @Column(type="string", name="description") */
    private $_description;
    /** @Column(type="boolean", name="private") */
    public $private = false;
    /** @Column(type="boolean", name="open") */
    public $open = true;
    
    
    /**
     * @ManyToOne(targetEntity="App\Entity\Community\Category")
     * @JoinColumns({
     *  @JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    
    private $_category;
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $_threads
     * 
     * @OneToMany(targetEntity="App\Entity\Community\Thread", mappedBy="_forum", cascade={"persist", "remove"})
     */
    private $_threads;
    
    public function __construct($category, $name, $description, $private = false, $open = true)
    {
        $this->setCategory($category);
        $this->_name = $name;
        $this->_description = $description;
        $this->_threads = new ArrayCollection();
        $this->private = $private;
        $this->open = $open;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setDescription($desc)
    {
        $this->_description = $desc;
        return $this;
    }
    
    public function getThreads()
    {
        return $this->_threads;
    }
    
    public function getThreadCount()
    {
        return count($this->getThreads());
    }
    
    public function getCategory()
    {
        return $this->_category;
    }
    
    public function setCategory($cat)
    {
       $this->_category = $cat;
    }
}