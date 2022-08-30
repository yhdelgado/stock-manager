<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"invoice" = "Invoice", "purchase" = "Purchase", "sale"="Sale"})
 */
class Invoice
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", length=100)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="invoice")
     */
    private $products;

    /**
     * @ORM\Column(type="decimal", length=50)
     */
    private $final_cost;

    /**
     * @ORM\ManyToOne(targetEntity="Warehouse", inversedBy="invoices")
     * @ORM\JoinColumn(name="warehouse_id", referencedColumnName="id")
     */
    private $warehouse;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of products
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get the value of final_cost
     */
    public function getFinal_cost()
    {
        return $this->final_cost;
    }

    /**
     * Set the value of final_cost
     *
     * @return  self
     */
    public function setFinal_cost($final_cost)
    {
        $this->final_cost = $final_cost;

        return $this;
    }
}
