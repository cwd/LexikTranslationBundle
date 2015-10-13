<?php

namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianUserRepository")
 */
class MorticianUser extends BaseUser
{
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(groups={"default"})
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
