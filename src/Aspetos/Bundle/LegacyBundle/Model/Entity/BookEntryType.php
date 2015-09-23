<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_bookEntryType", indexes={@ORM\Index(name="bookEntryTypePid", columns={"pid"})})
 */
class BookEntryType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     */
    private $typeId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $flash;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"NULL"})
     */
    private $hide;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailHeader1;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailBody1;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailHeader2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailBody2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailHeader3;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $emailBody3;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $maxLifetime;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $sort;

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param mixed $typeId
     *
     * @return $this
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCombined()
    {
        return $this->getTypeId().' - '.$this->getTitle().' ('.$this->getDomain().')';
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     *
     * @return $this
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFlash()
    {
        return $this->flash;
    }

    /**
     * @param mixed $flash
     *
     * @return $this
     */
    public function setFlash($flash)
    {
        $this->flash = $flash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @param mixed $hide
     *
     * @return $this
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailHeader1()
    {
        return $this->emailHeader1;
    }

    /**
     * @param mixed $emailHeader1
     *
     * @return $this
     */
    public function setEmailHeader1($emailHeader1)
    {
        $this->emailHeader1 = $emailHeader1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailBody1()
    {
        return $this->emailBody1;
    }

    /**
     * @param mixed $emailBody1
     *
     * @return $this
     */
    public function setEmailBody1($emailBody1)
    {
        $this->emailBody1 = $emailBody1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailHeader2()
    {
        return $this->emailHeader2;
    }

    /**
     * @param mixed $emailHeader2
     *
     * @return $this
     */
    public function setEmailHeader2($emailHeader2)
    {
        $this->emailHeader2 = $emailHeader2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailBody2()
    {
        return $this->emailBody2;
    }

    /**
     * @param mixed $emailBody2
     *
     * @return $this
     */
    public function setEmailBody2($emailBody2)
    {
        $this->emailBody2 = $emailBody2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailHeader3()
    {
        return $this->emailHeader3;
    }

    /**
     * @param mixed $emailHeader3
     *
     * @return $this
     */
    public function setEmailHeader3($emailHeader3)
    {
        $this->emailHeader3 = $emailHeader3;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailBody3()
    {
        return $this->emailBody3;
    }

    /**
     * @param mixed $emailBody3
     *
     * @return $this
     */
    public function setEmailBody3($emailBody3)
    {
        $this->emailBody3 = $emailBody3;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxLifetime()
    {
        return $this->maxLifetime;
    }

    /**
     * @param mixed $maxLifetime
     *
     * @return $this
     */
    public function setMaxLifetime($maxLifetime)
    {
        $this->maxLifetime = $maxLifetime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     *
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }
}
