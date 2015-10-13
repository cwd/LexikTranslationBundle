<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Mortician
{
    use Timestampable;
    use Blameable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", unique=true, length=250, nullable=false)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="phone_number", length=30, nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    private $phone;

    /**
     * @ORM\Column(type="phone_number", length=30, nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $webpage;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $commercialRegNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origMorticianId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crmId;

    /**
     * @ORM\Column(type="string", length=2, nullable=false, options={"default":"AT"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $contactName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $state;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $partnerVienna;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician")
     */
    private $obituaries;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="parentMortician")
     */
    private $morticians;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\MorticianMedia", mappedBy="mortician", cascade={"persist"})
     */
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="morticians")
     * @ORM\JoinColumn(name="parentMorticianId", referencedColumnName="id")
     */
    private $parentMortician;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="logoId", referencedColumnName="id")
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="avatarId", referencedColumnName="id")
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="morticians")
     * @ORM\JoinTable(
     *     name="MorticianHasCemetery",
     *     joinColumns={@ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $cemeteries;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="mortician")
     * @ORM\JoinTable(
     *     name="MorticianHasSupplier",
     *     joinColumns={@ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplier;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->morticians = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cemeteries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Mortician
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Mortician
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
     * Set description
     *
     * @param string $description
     * @return Mortician
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     * @return Mortician
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param integer $fax
     * @return Mortician
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return integer
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set webpage
     *
     * @param string $webpage
     * @return Mortician
     */
    public function setWebpage($webpage)
    {
        $this->webpage = $webpage;

        return $this;
    }

    /**
     * Get webpage
     *
     * @return string
     */
    public function getWebpage()
    {
        return $this->webpage;
    }

    /**
     * Set vat
     *
     * @param string $vat
     * @return Mortician
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set commercialRegNumber
     *
     * @param string $commercialRegNumber
     * @return Mortician
     */
    public function setCommercialRegNumber($commercialRegNumber)
    {
        $this->commercialRegNumber = $commercialRegNumber;

        return $this;
    }

    /**
     * Get commercialRegNumber
     *
     * @return string
     */
    public function getCommercialRegNumber()
    {
        return $this->commercialRegNumber;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Mortician
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
     * Set origMorticianId
     *
     * @param integer $origMorticianId
     * @return Mortician
     */
    public function setOrigMorticianId($origMorticianId)
    {
        $this->origMorticianId = $origMorticianId;

        return $this;
    }

    /**
     * Get origMorticianId
     *
     * @return integer
     */
    public function getOrigMorticianId()
    {
        return $this->origMorticianId;
    }

    /**
     * Set crmId
     *
     * @param integer $crmId
     * @return Mortician
     */
    public function setCrmId($crmId)
    {
        $this->crmId = $crmId;

        return $this;
    }

    /**
     * Get crmId
     *
     * @return integer
     */
    public function getCrmId()
    {
        return $this->crmId;
    }

    /**
     * Set address
     *
     * @param \Aspetos\Model\Entity\MorticianAddress $address
     * @return Mortician
     */
    public function setAddress(\Aspetos\Model\Entity\MorticianAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Aspetos\Model\Entity\MorticianAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add obituaries
     *
     * @param \Aspetos\Model\Entity\Obituary $obituaries
     * @return Mortician
     */
    public function addObituary(\Aspetos\Model\Entity\Obituary $obituaries)
    {
        $this->obituaries[] = $obituaries;

        return $this;
    }

    /**
     * Remove obituaries
     *
     * @param \Aspetos\Model\Entity\Obituary $obituaries
     */
    public function removeObituary(\Aspetos\Model\Entity\Obituary $obituaries)
    {
        $this->obituaries->removeElement($obituaries);
    }

    /**
     * Get obituaries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObituaries()
    {
        return $this->obituaries;
    }

    /**
     * Add morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     * @return Mortician
     */
    public function addMortician(\Aspetos\Model\Entity\Mortician $morticians)
    {
        $this->morticians[] = $morticians;

        return $this;
    }

    /**
     * Remove morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     */
    public function removeMortician(\Aspetos\Model\Entity\Mortician $morticians)
    {
        $this->morticians->removeElement($morticians);
    }

    /**
     * Get morticians
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMorticians()
    {
        return $this->morticians;
    }

    /**
     * Set parentMortician
     *
     * @param \Aspetos\Model\Entity\Mortician $parentMortician
     * @return Mortician
     */
    public function setParentMortician(\Aspetos\Model\Entity\Mortician $parentMortician = null)
    {
        $this->parentMortician = $parentMortician;

        return $this;
    }

    /**
     * Get parentMortician
     *
     * @return \Aspetos\Model\Entity\Mortician
     */
    public function getParentMortician()
    {
        return $this->parentMortician;
    }

    /**
     * Add cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     * @return Mortician
     */
    public function addCemetery(\Aspetos\Model\Entity\Cemetery $cemeteries)
    {
        $this->cemeteries[] = $cemeteries;

        return $this;
    }

    /**
     * Remove cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     */
    public function removeCemetery(\Aspetos\Model\Entity\Cemetery $cemeteries)
    {
        $this->cemeteries->removeElement($cemeteries);
    }

    /**
     * Get cemeteries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCemeteries()
    {
        return $this->cemeteries;
    }

    /**
     * Add supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return Mortician
     */
    public function addSupplier(\Aspetos\Model\Entity\Supplier $supplier)
    {
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     */
    public function removeSupplier(\Aspetos\Model\Entity\Supplier $supplier)
    {
        $this->supplier->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Mortician
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Mortician
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Mortician
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Mortician
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set registeredAt
     *
     * @param \DateTime $registeredAt
     * @return Mortician
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * Get registeredAt
     *
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * Add medias
     *
     * @param \Aspetos\Model\Entity\MorticianMedia $medias
     * @return Mortician
     */
    public function addMedia(\Aspetos\Model\Entity\MorticianMedia $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Aspetos\Model\Entity\MorticianMedia $medias
     */
    public function removeMedia(\Aspetos\Model\Entity\MorticianMedia $medias)
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
     * Set logo
     *
     * @param \Aspetos\Model\Entity\Media $logo
     * @return Mortician
     */
    public function setLogo(\Aspetos\Model\Entity\Media $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param Media $avatar
     *
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPartnerVienna()
    {
        return $this->partnerVienna;
    }

    /**
     * @deprecated use isPartnerVienna()
     * @return bool
     */
    public function getPartnerVienna()
    {
        return $this->isPartnerVienna();
    }

    /**
     * @param bool $partnerVienna
     *
     * @return $this
     */
    public function setPartnerVienna($partnerVienna)
    {
        $this->partnerVienna = $partnerVienna;

        return $this;
    }


}
