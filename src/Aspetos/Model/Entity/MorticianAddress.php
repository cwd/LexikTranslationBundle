<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianAddressRepository")
 */
class MorticianAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="address")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", unique=true)
     */
    private $mortician;

    /**
     * Set mortician
     *
     * @param \Aspetos\Model\Entity\Mortician $mortician
     * @return MorticianAddress
     */
    public function setMortician(\Aspetos\Model\Entity\Mortician $mortician = null)
    {
        $this->mortician = $mortician;

        return $this;
    }

    /**
     * Get mortician
     *
     * @return \Aspetos\Model\Entity\Mortician 
     */
    public function getMortician()
    {
        return $this->mortician;
    }
}
