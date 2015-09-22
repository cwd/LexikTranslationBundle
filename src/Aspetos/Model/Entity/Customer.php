<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Customer extends \Aspetos\Model\Entity\User
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aspetos\Model\Entity\CustomerAddress",
     *     mappedBy="customer",
     *     cascade={"persist","remove"}
     * )
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\CustomerOrder", mappedBy="customer")
     */
    private $orders;
}