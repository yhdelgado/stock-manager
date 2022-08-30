<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $upc;

    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", length=15)
     */
    private $cost;

    /**
     * @ORM\Column(type="decimal", length=15)
     */
    private $sell_price;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weight_gr;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weight_oz;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weight_lb;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $description_en;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $description_es;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $photo;
    /**
     * @ORM\Column(type="date", length=20)
     */
    private $exp_date;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="products")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $invoice;
}
