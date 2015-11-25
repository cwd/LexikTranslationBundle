<?php

namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierUserRepository")
 */
class SupplierUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="supplierUsers")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", unique=true)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="users")
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
