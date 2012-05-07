<?php
namespace App\Entity;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Tag")
 * @Table(name="tags")
 */
class Tag
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    /**
     * @ManyToMany(targetEntity="App\Entity\Article", mappedBy="_tags", cascade={"persist"})
     */
    private $_articles;
    /** @Column(type="string", name="title") */
    private $_title;
    /** @Column(type="string", name="class") */
    private $_class;
    
    public function __construct($title, $class = "label")
    {
        $this->_title = $title;
        $this->_class = $class;
        $this->_articles = new ArrayCollection();
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }
    
    public function getClass()
    {
        return $this->_class;
    }
    
    public function setClass($class)
    {
        $this->_class = $class;
        return $class;
    }
    
    public function addArticle(\App\Entity\Article $article)
    {
        $this->_articles[] = $article;
    }
}