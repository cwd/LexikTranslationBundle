<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ObituaryRepository")
 */
class Obituary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

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
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="obituary", cascade={"persist"})
     * @ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)
     */
    private $cemetery;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="obituaries")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)
     */
    private $mortician;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="obituaries")
     */
    private $suppliers;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->condolences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->candles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
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
}
