<?php
namespace App\Entity\Community;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Community\Group")
 * @Table(name="groups")
 */
class Group
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
    
    
    public function __construct($name, $description, $private = false, $open = true)
    {
        $this->_name = $name;
        $this->_description = $description;
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
}