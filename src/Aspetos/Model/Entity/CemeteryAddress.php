<?php
namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryAddressRepository")
 */
class CemeteryAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $district;

    /**
     * @ORM\Column(type="float", length=10, nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", length=10, nullable=true)
     */
    private $lat;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="address", cascade={"persist"})
     * @ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false, unique=true)
     */
    private $cemetery;

    /**
     * Set lng
     *
     * @param float $lng
     * @return CemeteryAddress
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return CemeteryAddress
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     * @return CemeteryAddress
     */
    public function setCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery = $cemetery;

        return $this;
    }

    /**
     * Get cemetery
     *
     * @return \Aspetos\Model\Entity\Cemetery
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }

    /**
     * @return mixed
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param mixed $district
     *
     * @return $this
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }
}
