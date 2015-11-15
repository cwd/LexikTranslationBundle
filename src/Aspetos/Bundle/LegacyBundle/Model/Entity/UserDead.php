<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
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
 *
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
     * @ORM\Column(type="string", nullable=true, options={"default":"'deathnotice'"})
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
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
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
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
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
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
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

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getDeathnoticeStandard()
    {
        return $this->deathnoticeStandard;
    }

    /**
     * @return mixed
     */
    public function getMotherForename()
    {
        return $this->motherForename;
    }

    /**
     * @return mixed
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * @return mixed
     */
    public function getFatherForename()
    {
        return $this->fatherForename;
    }

    /**
     * @return mixed
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * @return mixed
     */
    public function getPartnerForename()
    {
        return $this->partnerForename;
    }

    /**
     * @return mixed
     */
    public function getPartnerName()
    {
        return $this->partnerName;
    }

    /**
     * @return mixed
     */
    public function getLastPlace()
    {
        return $this->lastPlace;
    }

    /**
     * @return mixed
     */
    public function getDateTime1()
    {
        return $this->dateTime1;
    }

    /**
     * @return mixed
     */
    public function getDateTitle1()
    {
        return $this->dateTitle1;
    }

    /**
     * @return mixed
     */
    public function getDateTitleStandard1()
    {
        return $this->dateTitleStandard1;
    }

    /**
     * @return mixed
     */
    public function getDateDescription1()
    {
        return $this->dateDescription1;
    }

    /**
     * @return mixed
     */
    public function getDateTime2()
    {
        return $this->dateTime2;
    }

    /**
     * @return mixed
     */
    public function getDateTitle2()
    {
        return $this->dateTitle2;
    }

    /**
     * @return mixed
     */
    public function getDateTitleStandard2()
    {
        return $this->dateTitleStandard2;
    }

    /**
     * @return mixed
     */
    public function getDateDescription2()
    {
        return $this->dateDescription2;
    }

    /**
     * @return mixed
     */
    public function getDateTime3()
    {
        return $this->dateTime3;
    }

    /**
     * @return mixed
     */
    public function getDateTitle3()
    {
        return $this->dateTitle3;
    }

    /**
     * @return mixed
     */
    public function getDateTitleStandard3()
    {
        return $this->dateTitleStandard3;
    }

    /**
     * @return mixed
     */
    public function getDateDescription3()
    {
        return $this->dateDescription3;
    }
}
