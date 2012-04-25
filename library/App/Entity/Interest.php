<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Interest")
 * @Table(name="interests")
 */
class Interest
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="string", name="name") */
    private $_name;
    /** @Column(type="text", name="name", length="140") */
    private $_description;
    
    public function __construct($name, $description)
    {
        $this->_name = $name;
        $this->_description;
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