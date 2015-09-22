<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianAddressRepository")
 */
class MorticianAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="addresses")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", unique=true)
     */
    private $mortician;
}