<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class SupplierUser extends \Aspetos\Model\Entity\User
{
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)
     */
    private $supplier;
}