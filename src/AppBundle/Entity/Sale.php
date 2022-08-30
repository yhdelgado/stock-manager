<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sale")
 */
class Sale extends Invoice
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $client;
}
