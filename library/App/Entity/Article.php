<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Quote")
 * @Table(name="quote")
 */
class Quote
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    /** @Column(type="string", name="wording") */
    private $_wording;
    /** @Column(type="string", name="author") */
    private $_author;
    /** @Column(type="string", name="source")*/
    private $_source;
    
    public function getId()
    {
        return $this->_id;
    }

    public function getWording()
    {
        return $this->_wording;
    }

    public function setWording($wording)
    {
        $this->_wording = $wording;
        return $this;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setAuthor($author)
    {
        $this->_author = $author;
        return $this;
    }
    
    public function getSource()
    {
        return $this->_source;
    }
    
    public function setSource($source)
    {
        $this->_source = $source;
        return $this;
    }
    
    
    public function __toString()
    {
        return $this->getWording();
    }



}