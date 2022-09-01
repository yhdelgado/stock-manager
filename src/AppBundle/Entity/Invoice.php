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
 * @DiscriminatorMap({"invoice" = "Invoice", "sale"="Sale"})
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
     * @ORM\Column(type="decimal", length=50, nullable=true)
     */
    private $finalCost;

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
    public function getFinalCost()
    {
        return $this->finalCost;
    }

    /**
     * Set the value of final_cost
     *
     * @return  self
     */
    public function setFinalCost($finalCost)
    {
        $this->finalCost = $finalCost;

        return $this;
    }

    /**
     * Get the value of warehouse
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * Set the value of warehouse
     *
     * @return  self
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;

        return $this;
    }
}
