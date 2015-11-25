<?php

namespace Aspetos\Model\Entity;

use Aspetos\Service\UserInterface as AspetosUserInterface;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierUserRepository")
 */
class SupplierUser implements AspetosUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="supplierUser")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=false, unique=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="users")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(groups={"default"})
     */
    private $supplier;

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

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Aspetos\Model\Entity\BaseUser $user
     * @return SupplierUser
     */
    public function setUser(\Aspetos\Model\Entity\BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Aspetos\Model\Entity\BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
