<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class SupplierAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="address")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", unique=true)
     */
    private $supplier;
}