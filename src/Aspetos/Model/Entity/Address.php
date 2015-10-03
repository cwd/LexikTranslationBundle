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
 *     "cemetryAdministration"="Aspetos\Model\Entity\CemeteryAdministration",
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
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Region",cascade={"persist"})
     * @ORM\JoinColumn(name="regionId", referencedColumnName="id", nullable=false)
     */
    private $region;

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
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street2
     *
     * @param string $street2
     * @return Address
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * Get street2
     *
     * @return string 
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     * @return Address
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param \Aspetos\Model\Entity\Region $region
     * @return Address
     */
    public function setRegion(\Aspetos\Model\Entity\Region $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Aspetos\Model\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
}
