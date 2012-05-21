<?php
namespace App\Entity\Community\Exchange;
use App\Classes\Cart\Item as BaseItem;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\Community\Exchange\Item")
 * @Table(name="items")
 */
class Item extends BaseItem
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    protected $_manufacturer;
    
    protected $_productName;
    
    protected $_price;
    
    public function __construct($manufacturer, $name, $price)
    {
        parent::__construct($this->_id, $name, $price, 1);
        
        $this->_manufacturer = $manufacturer;
        $this->_productName = $name;
        $this->_price = $price;
    }
    
    public function getId()
    {
        return $this->_id;
    }
}