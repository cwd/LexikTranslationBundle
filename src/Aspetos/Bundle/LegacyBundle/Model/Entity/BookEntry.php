<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_bookEntry",
 *     indexes={
 *         @ORM\Index(name="activateUid", columns={"activateUid"}),
 *         @ORM\Index(name="bookId", columns={"bookId"}),
 *         @ORM\Index(name="es_bookEntryType_typeId", columns={"type"}),
 *         @ORM\Index(name="es_bookEntryOrderId", columns={"orderId"}),
 *         @ORM\Index(name="hide", columns={"hide"}),
 *         @ORM\Index(name="datetime", columns={"datetime"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="entryIdOldSystem", columns={"entryIdOldSystem"})}
 * )
 */
class BookEntry
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $entryId;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $entryIdOldSystem;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $bookId;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $orderId;

    /**
     * @ORM\ManyToOne(targetEntity="BookEntryType")
     * @ORM\JoinColumn(name="type", referencedColumnName="typeId")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $hide;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $ip;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $orderProductId;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @ORM\Column(type="string", length=150, nullable=true, options={"default":"NULL"})
     */
    private $activateEmail;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $activateCode;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $activateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $expireDate;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $expireEmailSent;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"'0'"})
     */
    private $anniversaryReminder;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"'0'"})
     */
    private $infoservice;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @return mixed
     */
    public function getEntryId()
    {
        return $this->entryId;
    }

    /**
     * @param mixed $entryId
     *
     * @return $this
     */
    public function setEntryId($entryId)
    {
        $this->entryId = $entryId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntryIdOldSystem()
    {
        return $this->entryIdOldSystem;
    }

    /**
     * @param mixed $entryIdOldSystem
     *
     * @return $this
     */
    public function setEntryIdOldSystem($entryIdOldSystem)
    {
        $this->entryIdOldSystem = $entryIdOldSystem;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @param mixed $bookId
     *
     * @return $this
     */
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderProductId()
    {
        return $this->orderProductId;
    }

    /**
     * @param mixed $orderProductId
     *
     * @return $this
     */
    public function setOrderProductId($orderProductId)
    {
        $this->orderProductId = $orderProductId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     *
     * @return $this
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivateEmail()
    {
        return $this->activateEmail;
    }

    /**
     * @param mixed $activateEmail
     *
     * @return $this
     */
    public function setActivateEmail($activateEmail)
    {
        $this->activateEmail = $activateEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivateCode()
    {
        return $this->activateCode;
    }

    /**
     * @param mixed $activateCode
     *
     * @return $this
     */
    public function setActivateCode($activateCode)
    {
        $this->activateCode = $activateCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivateDate()
    {
        return $this->activateDate;
    }

    /**
     * @param mixed $activateDate
     *
     * @return $this
     */
    public function setActivateDate($activateDate)
    {
        $this->activateDate = $activateDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param mixed $expireDate
     *
     * @return $this
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpireEmailSent()
    {
        return $this->expireEmailSent;
    }

    /**
     * @param mixed $expireEmailSent
     *
     * @return $this
     */
    public function setExpireEmailSent($expireEmailSent)
    {
        $this->expireEmailSent = $expireEmailSent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnniversaryReminder()
    {
        return $this->anniversaryReminder;
    }

    /**
     * @param mixed $anniversaryReminder
     *
     * @return $this
     */
    public function setAnniversaryReminder($anniversaryReminder)
    {
        $this->anniversaryReminder = $anniversaryReminder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfoservice()
    {
        return $this->infoservice;
    }

    /**
     * @param mixed $infoservice
     *
     * @return $this
     */
    public function setInfoservice($infoservice)
    {
        $this->infoservice = $infoservice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }


}
