<?php
namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ObituaryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Obituary
{
    use Timestampable;
    use Blameable;

    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';
    const GENDER_UNDEF = 'u';

    const TYPE_NORMAL = 1;
    const TYPE_PROMINENT = 2;
    const TYPE_CHILD = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1, nullable=false, options={"default":"u"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $titlePrefix;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $titlePostfix;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $bornAs;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dayOfBirth;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dayOfDeath;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     * Gedmo\Slug(fields={"firstname", "lastname", "bornAs"})
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="district"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"firstname", "lastname", "bornAs"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @Assert\Length(groups={"default"}, max = 2)
     * @Assert\NotBlank(groups={"default"})
     * @ORM\Column(type="string", length=2, nullable=false, options={"default":"AT"})
     */
    private $country;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false, options={"default":1})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $customerId;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $allowCondolence;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $showOnlyBirthYear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $legacyCemetery;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ObituaryEvent", mappedBy="obituary")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Condolence", mappedBy="obituary", cascade={"persist"})
     */
    private $condolences;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Candle", mappedBy="obituary")
     */
    private $candles;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ObituaryMedia", mappedBy="obituary", cascade={"persist"})
     * @ORM\OrderBy({"type" = "ASC"})
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Reminder", mappedBy="obituary", cascade={"persist"})
     */
    private $reminders;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="obituary", cascade={"persist"})
     * @ORM\JoinColumn(name="cemeteryId", referencedColumnName="id")
     */
    private $cemetery;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="obituaries")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id")
     */
    private $mortician;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Customer", inversedBy="obituary")
     * 
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id")
     */
    private $obituary;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="portraitId", referencedColumnName="id")
     */
    private $portrait;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\District", inversedBy="obituary")
     * @ORM\JoinColumn(name="districtId", referencedColumnName="id")
     */
    private $district;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="obituaries")
     */
    private $suppliers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->condolences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->candles     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suppliers   = new \Doctrine\Common\Collections\ArrayCollection();

        $this->gender            = self::GENDER_UNDEF;
        $this->type              = self::TYPE_NORMAL;
        $this->hide              = 0;
        $this->showOnlyBirthYear = 0;
        $this->allowCondolence   = 1;
    }

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
     * Set firstname
     *
     * @param string $firstname
     * @return Obituary
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Obituary
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set titlePrefix
     *
     * @param string $titlePrefix
     * @return Obituary
     */
    public function setTitlePrefix($titlePrefix)
    {
        $this->titlePrefix = $titlePrefix;

        return $this;
    }

    /**
     * Get titlePrefix
     *
     * @return string
     */
    public function getTitlePrefix()
    {
        return $this->titlePrefix;
    }

    /**
     * Set titlePostfix
     *
     * @param string $titlePostfix
     * @return Obituary
     */
    public function setTitlePostfix($titlePostfix)
    {
        $this->titlePostfix = $titlePostfix;

        return $this;
    }

    /**
     * Get titlePostfix
     *
     * @return string
     */
    public function getTitlePostfix()
    {
        return $this->titlePostfix;
    }

    /**
     * Set bornAs
     *
     * @param string $bornAs
     * @return Obituary
     */
    public function setBornAs($bornAs)
    {
        $this->bornAs = $bornAs;

        return $this;
    }

    /**
     * Get bornAs
     *
     * @return string
     */
    public function getBornAs()
    {
        return $this->bornAs;
    }

    /**
     * Set dayOfBirth
     *
     * @param \DateTime $dayOfBirth
     * @return Obituary
     */
    public function setDayOfBirth($dayOfBirth)
    {
        $this->dayOfBirth = $dayOfBirth;

        return $this;
    }

    /**
     * Get dayOfBirth
     *
     * @return \DateTime
     */
    public function getDayOfBirth()
    {
        return $this->dayOfBirth;
    }

    /**
     * Set dayOfDeath
     *
     * @param \DateTime $dayOfDeath
     * @return Obituary
     */
    public function setDayOfDeath($dayOfDeath)
    {
        $this->dayOfDeath = $dayOfDeath;

        return $this;
    }

    /**
     * Get dayOfDeath
     *
     * @return \DateTime
     */
    public function getDayOfDeath()
    {
        return $this->dayOfDeath;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Obituary
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Obituary
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Add events
     *
     * @param \Aspetos\Model\Entity\ObituaryEvent $events
     * @return Obituary
     */
    public function addEvent(\Aspetos\Model\Entity\ObituaryEvent $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Aspetos\Model\Entity\ObituaryEvent $events
     */
    public function removeEvent(\Aspetos\Model\Entity\ObituaryEvent $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add condolences
     *
     * @param \Aspetos\Model\Entity\Condolence $condolences
     * @return Obituary
     */
    public function addCondolence(\Aspetos\Model\Entity\Condolence $condolences)
    {
        $this->condolences[] = $condolences;

        return $this;
    }

    /**
     * Remove condolences
     *
     * @param \Aspetos\Model\Entity\Condolence $condolences
     */
    public function removeCondolence(\Aspetos\Model\Entity\Condolence $condolences)
    {
        $this->condolences->removeElement($condolences);
    }

    /**
     * Get condolences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCondolences()
    {
        return $this->condolences;
    }

    /**
     * Add candles
     *
     * @param \Aspetos\Model\Entity\Candle $candles
     * @return Obituary
     */
    public function addCandle(\Aspetos\Model\Entity\Candle $candles)
    {
        $this->candles[] = $candles;

        return $this;
    }

    /**
     * Remove candles
     *
     * @param \Aspetos\Model\Entity\Candle $candles
     */
    public function removeCandle(\Aspetos\Model\Entity\Candle $candles)
    {
        $this->candles->removeElement($candles);
    }

    /**
     * Get candles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCandles()
    {
        return $this->candles;
    }

    /**
     * Set cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     * @return Obituary
     */
    public function setCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery = $cemetery;

        return $this;
    }

    /**
     * Get cemetery
     *
     * @return \Aspetos\Model\Entity\Cemetery
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }

    /**
     * Set mortician
     *
     * @param \Aspetos\Model\Entity\Mortician $mortician
     * @return Obituary
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
     * Add suppliers
     *
     * @param \Aspetos\Model\Entity\Supplier $suppliers
     * @return Obituary
     */
    public function addSupplier(\Aspetos\Model\Entity\Supplier $suppliers)
    {
        $this->suppliers[] = $suppliers;

        return $this;
    }

    /**
     * Remove suppliers
     *
     * @param \Aspetos\Model\Entity\Supplier $suppliers
     */
    public function removeSupplier(\Aspetos\Model\Entity\Supplier $suppliers)
    {
        $this->suppliers->removeElement($suppliers);
    }

    /**
     * Get suppliers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHide()
    {
        return $this->hide;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hide;
    }

    /**
     * @param bool $hide
     *
     * @return $this
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAllowCondolence()
    {
        return $this->allowCondolence;
    }

    /**
     * @param bool $allowCondolence
     *
     * @return $this
     */
    public function setAllowCondolence($allowCondolence)
    {
        $this->allowCondolence = $allowCondolence;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowOnlyBirthYear()
    {
        return $this->showOnlyBirthYear;
    }

    /**
     * @param mixed $showOnlyBirthYear
     *
     * @return $this
     */
    public function setShowOnlyBirthYear($showOnlyBirthYear)
    {
        $this->showOnlyBirthYear = $showOnlyBirthYear;

        return $this;
    }

    /**
     * Set customer
     *
     * @param \Aspetos\Model\Entity\Customer $customer
     * @return Obituary
     */
    public function setCustomer(\Aspetos\Model\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Aspetos\Model\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Media $obituary
     * @return Obituary
     */
    public function setObituary(\Aspetos\Model\Entity\Media $obituary = null)
    {
        $this->obituary = $obituary;

        return $this;
    }

    /**
     * Get obituary
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getObituary()
    {
        return $this->obituary;
    }

    /**
     * Set portrait
     *
     * @param \Aspetos\Model\Entity\Media $portrait
     * @return Obituary
     */
    public function setPortrait(\Aspetos\Model\Entity\Media $portrait = null)
    {
        $this->portrait = $portrait;

        return $this;
    }

    /**
     * Get portrait
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getPortrait()
    {
        return $this->portrait;
    }

    /**
     * @return int
     */
    public function getOrigId()
    {
        return $this->origId;
    }

    /**
     * @param int $origId
     *
     * @return $this
     */
    public function setOrigId($origId)
    {
        $this->origId = $origId;

        return $this;
    }

    /**
     * Get Age
     * @return null|string
     */
    public function getAge()
    {
        if ($this->getDayOfBirth() == null || $this->getDayOfDeath() == null) {
            return null;
        }

        $interval = $this->getDayOfDeath()->diff($this->getDayOfBirth());

        return $interval->format("%y");
    }

    /**
     * Set legacyCemetery
     *
     * @param string $legacyCemetery
     * @return Obituary
     */
    public function setLegacyCemetery($legacyCemetery)
    {
        $this->legacyCemetery = $legacyCemetery;

        return $this;
    }

    /**
     * Get legacyCemetery
     *
     * @return string
     */
    public function getLegacyCemetery()
    {
        return $this->legacyCemetery;
    }

    /**
     * Set district
     *
     * @param \Aspetos\Model\Entity\District $district
     * @return Obituary
     */
    public function setDistrict(\Aspetos\Model\Entity\District $district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \Aspetos\Model\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Add medias
     *
     * @param \Aspetos\Model\Entity\ObituaryMedia $medias
     * @return Obituary
     */
    public function addMedia(\Aspetos\Model\Entity\ObituaryMedia $medias)
    {
        $this->medias[] = $medias;
        $medias->setObituary($this);

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Aspetos\Model\Entity\ObituaryMedia $medias
     */
    public function removeMedia(\Aspetos\Model\Entity\ObituaryMedia $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Get hide
     *
     * @return boolean
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * Get allowCondolence
     *
     * @return boolean
     */
    public function getAllowCondolence()
    {
        return $this->allowCondolence;
    }

    /**
     * Get showOnlyBirthYear
     *
     * @return boolean
     */
    public function getShowOnlyBirthYear()
    {
        return $this->showOnlyBirthYear;
    }

    /**
     * Add reminders
     *
     * @param \Aspetos\Model\Entity\Reminder $reminders
     * @return Obituary
     */
    public function addReminder(\Aspetos\Model\Entity\Reminder $reminders)
    {
        $reminders->setObituary($this);
        $this->reminders[] = $reminders;

        return $this;
    }

    /**
     * Remove reminders
     *
     * @param \Aspetos\Model\Entity\Reminder $reminders
     */
    public function removeReminder(\Aspetos\Model\Entity\Reminder $reminders)
    {
        $this->reminders->removeElement($reminders);
    }

    /**
     * Get reminders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReminders()
    {
        return $this->reminders;
    }
}
