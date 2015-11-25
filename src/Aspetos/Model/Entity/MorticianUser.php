<?php

namespace Aspetos\Model\Entity;

use Aspetos\Service\UserInterface as AspetosUserInterface;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianUserRepository")
 */
class MorticianUser implements AspetosUserInterface
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="morticianUser")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=false, unique=true)
     * @ORM\Id
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="users")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(groups={"default"})
     */
    private $mortician;

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

    /**
     * Get id
     * @deprecated for bc
     * @return integer
     */
    public function getId()
    {
        return $this->getUser()->getId();
    }

    /**
     * Set user
     *
     * @param \Aspetos\Model\Entity\BaseUser $user
     * @return MorticianUser
     */
    public function setUser(\Aspetos\Model\Entity\BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Aspetos\Model\Entity\BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
