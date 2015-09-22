<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_user_dead",
 *     indexes={
 *         @ORM\Index(name="userDeadUid", columns={"uid"}),
 *         @ORM\Index(name="userDeadPartnerGenderFor", columns={"partnerGender"}),
 *         @ORM\Index(name="userDeadCauseOfDeadId", columns={"causeOfDeathId"}),
 *         @ORM\Index(name="es_user_dead_deadTime1", columns={"dateTime1"}),
 *         @ORM\Index(name="es_user_dead_deadTime2", columns={"dateTime2"}),
 *         @ORM\Index(name="es_user_dead_deadTime3", columns={"dateTime3"}),
 *         @ORM\Index(name="es_user_dead_deadTitleStandard1", columns={"dateTitleStandard1"}),
 *         @ORM\Index(name="es_user_dead_deadTitleStandard2", columns={"dateTitleStandard2"}),
 *         @ORM\Index(name="es_user_dead_deadTitleStandard3", columns={"dateTitleStandard3"})
 *     }
 * )
 */
class UserDead
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uid;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"'deathnotice'"})
     */
    private $deathnoticeStandard;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $motherForename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $motherName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $fatherForename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $fatherName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $partnerForename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $partnerName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $lastPlace;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $dateTime1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $dateTitle1;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateTitleStandard1;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateDescription1;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $dateTime2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $dateTitle2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateTitleStandard2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateDescription2;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $dateTime3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $dateTitle3;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateTitleStandard3;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $dateDescription3;
}
