<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierAddressRepository")
 */
class SupplierAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="address")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", unique=true)
     */
    private $supplier;

    /**
     * Set supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return SupplierAddress
     */
    public function setSupplier(\Aspetos\Model\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return \Aspetos\Model\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}
