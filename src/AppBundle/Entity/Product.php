<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    private $productName;

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
    private $sellPrice;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weightGr;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weightOz;

    /**
     * @ORM\Column(type="decimal", length=10)
     */
    private $weightLb;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descriptionEs;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\File(mimeTypes={ "image/jpeg", "image/gif",    "image/png" }, mimeTypesMessage = "Please upload a valid Image")
     */
    private $photo;

    /**
     * @ORM\Column(type="date", length=20)
     */
    private $expDate;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * @param mixed $upc
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * @param mixed $sellPrice
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;
    }

    /**
     * @return mixed
     */
    public function getWeightGr()
    {
        return $this->weightGr;
    }

    /**
     * @param mixed $weightGr
     */
    public function setWeightGr($weightGr)
    {
        $this->weightGr = $weightGr;
    }

    /**
     * @return mixed
     */
    public function getWeightOz()
    {
        return $this->weightOz;
    }

    /**
     * @param mixed $weightOz
     */
    public function setWeightOz($weightOz)
    {
        $this->weightOz = $weightOz;
    }

    /**
     * @return mixed
     */
    public function getWeightLb()
    {
        return $this->weightLb;
    }

    /**
     * @param mixed $weightLb
     */
    public function setWeightLb($weightLb)
    {
        $this->weightLb = $weightLb;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param mixed $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * @return mixed
     */
    public function getDescriptionEs()
    {
        return $this->descriptionEs;
    }

    /**
     * @param mixed $descriptionEs
     */
    public function setDescriptionEs($descriptionEs)
    {
        $this->descriptionEs = $descriptionEs;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getExpDate()
    {
        return $this->expDate;
    }

    /**
     * @param mixed $expDate
     */
    public function setExpDate($expDate)
    {
        $this->expDate = $expDate;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

}
