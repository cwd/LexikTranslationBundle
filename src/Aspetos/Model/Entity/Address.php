<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\AddressRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "customer"="Aspetos\Model\Entity\CustomerAddress",
 *     "supplier"="Aspetos\Model\Entity\SupplierAddress",
 *     "mortician"="Aspetos\Model\Entity\MorticianAddress",
 *     "cemetryAdministration"="Aspetos\Model\Entity\CemetryAdministration",
 *     "cemetery"="Aspetos\Model\Entity\CemeteryAddress"
 * }
 * )
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $street2;

    /**
     * @ORM\Column(type="integer", length=5, nullable=false)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Region")
     * @ORM\JoinColumn(name="regionId", referencedColumnName="id", nullable=false)
     */
    private $region;
}