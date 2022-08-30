<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="purchase")
 */
class Purchase extends Invoice
{
    /**
     * @ORM\ManyToOne(targetEntity="Warehouse", inversedBy="invoices")
     * @ORM\JoinColumn(name="warehouse_id", referencedColumnName="id")
     */
    private $warehouse;

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
