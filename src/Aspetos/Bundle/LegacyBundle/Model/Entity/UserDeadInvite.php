<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_user_dead_invite")
 */
class UserDeadInvite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $iid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $uidTo;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $addedUid;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $addedDate;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $emailSent;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $visitedDate;
}