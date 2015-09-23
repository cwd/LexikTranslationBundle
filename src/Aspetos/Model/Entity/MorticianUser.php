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
}
