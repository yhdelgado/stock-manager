<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="enterprise")
 */
class Enterprise
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
     * @ORM\OneToMany(targetEntity="Warehouse", mappedBy="enterprise")
     */
    private $wharehouses;

    public function __construct()
    {
        $this->wharehouses = new ArrayCollection();
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
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of wharehouses
     */
    public function getWharehouses()
    {
        return $this->wharehouses;
    }

    /**
     * Set the value of wharehouses
     *
     * @return  self
     */
    public function setWharehouses($wharehouses)
    {
        $this->wharehouses = $wharehouses;

        return $this;
    }
}
