<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\AdminRepository")
 */
class Admin extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="admins")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", unique=true)
     */
    private $user;
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_ADMIN;
    }
}
