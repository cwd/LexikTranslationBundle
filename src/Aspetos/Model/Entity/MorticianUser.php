<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianUserRepository")
 */
class MorticianUser extends \Aspetos\Model\Entity\User
{
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)
     */
    private $mortician;

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_MORTICIAN;
    }

    /**
     * Set mortician
     *
     * @param \Aspetos\Model\Entity\Mortician $mortician
     * @return MorticianUser
     */
    public function setMortician(\Aspetos\Model\Entity\Mortician $mortician)
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
