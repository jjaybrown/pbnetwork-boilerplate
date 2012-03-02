<?php
namespace App\Entity\Checkout;


/**
 *@Entity(repositoryClass="App\Repository\Checkout\Transaction")
 * @Table(name="transaction")
 */

class Transaction
{
    /**
     *@Id @Column(type="integer", name="id")
     * @GeneratedValue 
     */
    private $_id;
    
}