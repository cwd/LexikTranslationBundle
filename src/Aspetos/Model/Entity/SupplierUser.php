<?php

namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierUserRepository")
 */
class SupplierUser extends \Aspetos\Model\Entity\User
{
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(groups={"default"})
     */
    private $supplier;

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_SUPPLIER;
    }

    /**
     * Set supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return SupplierUser
     */
    public function setSupplier(\Aspetos\Model\Entity\Supplier $supplier)
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
