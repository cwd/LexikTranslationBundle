<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryAddressRepository")
 */
class CemeteryAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\Column(type="float", length=10, nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", length=10, nullable=true)
     */
    private $lat;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="address")
     * @ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false, unique=true)
     */
    private $cemetery;
}