<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="warehouse")
 */
class Warehouse
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
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="Enterprise", inversedBy="wharehouses")
     * @ORM\JoinColumn(name="enterprise_id", referencedColumnName="id")
     */
    private $enterprise;


    /*     /**
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="warehouse_product",
     *      joinColumns={@ORM\JoinColumn(name="warehouse_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    //private $products;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="warehouse")
     */
    private $invoices;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of lat
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of lat
     *
     * @return  self
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;

        return $this;
    }

    /**
     * Get the value of long
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of long
     *
     * @return  self
     */
    public function setLongitude($long)
    {
        $this->longitude = $long;

        return $this;
    }

    /**
     * Get the value of enterprise
     */
    public function getEnterprise()
    {
        return $this->enterprise;
    }

    /**
     * Set the value of enterprise
     *
     * @return  self
     */
    public function setEnterprise($enterprise)
    {
        $this->enterprise = $enterprise;

        return $this;
    }
}
