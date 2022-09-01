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
    private $product_name;

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

    /**
     * Get the value of name
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Get the value of name
     */
    public function getproduct_name()
    {
        return $this->product_name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;

        return $this;
    }

    /**
     * Get the value of upc
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * Set the value of upc
     *
     * @return  self
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;

        return $this;
    }

    /**
     * Get the value of amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @return  self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of cost
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     *
     * @return  self
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get the value of sell_price
     */
    public function getSellPrice()
    {
        return $this->sell_price;
    }

    /**
     * Set the value of sell_price
     *
     * @return  self
     */
    public function setSellPrice($sell_price)
    {
        $this->sell_price = $sell_price;

        return $this;
    }

    /**
     * Get the value of weight_gr
     */
    public function getWeightGr()
    {
        return $this->weight_gr;
    }

    /**
     * Set the value of weight_gr
     *
     * @return  self
     */
    public function setWeightGr($weight_gr)
    {
        $this->weight_gr = $weight_gr;

        return $this;
    }

    /**
     * Get the value of weight_oz
     */
    public function getWeightOz()
    {
        return $this->weight_oz;
    }

    /**
     * Set the value of weight_oz
     *
     * @return  self
     */
    public function setWeightOz($weight_oz)
    {
        $this->weight_oz = $weight_oz;

        return $this;
    }

    /**
     * Get the value of weight_lb
     */
    public function getWeightLb()
    {
        return $this->weight_lb;
    }

    /**
     * Set the value of weight_lb
     *
     * @return  self
     */
    public function setWeightLb($weight_lb)
    {
        $this->weight_lb = $weight_lb;

        return $this;
    }

    /**
     * Get the value of brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @return  self
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of description_en
     */
    public function getDescriptionEn()
    {
        return $this->description_en;
    }

    /**
     * Set the value of description_en
     *
     * @return  self
     */
    public function setDescriptionEn($description_en)
    {
        $this->description_en = $description_en;

        return $this;
    }

    /**
     * Get the value of description_es
     */
    public function getDescriptionEs()
    {
        return $this->description_es;
    }

    /**
     * Set the value of description_es
     *
     * @return  self
     */
    public function setDescriptionEs($description_es)
    {
        $this->description_es = $description_es;

        return $this;
    }

    /**
     * Get the value of photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of exp_date
     */
    public function getExpDate()
    {
        return $this->exp_date;
    }

    /**
     * Set the value of exp_date
     *
     * @return  self
     */
    public function setExpDate($exp_date)
    {
        $this->exp_date = $exp_date;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set the value of invoice
     *
     * @return  self
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getWarehouse()
    {
        return $this->invoice->getWarehouse();
    }
}
