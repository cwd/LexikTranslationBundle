<?php
namespace Aspetos\Model\Entity;
use Aspetos\Service\UserInterface as AspetosUserInterface;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\AdminRepository")
 */
class Admin implements AspetosUserInterface
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="admin", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="id", nullable=false, unique=true)
     */
    private $user;

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
     * Set user
     *
     * @param \Aspetos\Model\Entity\BaseUser $user
     * @return Admin
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
