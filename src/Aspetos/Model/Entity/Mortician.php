<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

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
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 250)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Length(groups={"default"}, max = 150)
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
     * @Assert\Email(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Url(groups={"default"})
     * @Assert\Length(max = "200", groups={"default"})
     */
    private $webpage;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = "30", groups={"default"})
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = "30", groups={"default"})
     */
    private $commercialRegNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
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
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 2)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = "200", groups={"default"})
     */
    private $contactName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
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
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician")
     */
    private $obituaries;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\MorticianUser", mappedBy="mortician")
     */
    private $users;

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
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="logoId", referencedColumnName="id")
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media", cascade={"persist"})
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
        $this->obituaries = new ArrayCollection();
        $this->morticians = new ArrayCollection();
        $this->cemeteries = new ArrayCollection();
        $this->supplier = new ArrayCollection();
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
     * @param string $phone
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
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
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
     * @return string
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
     * @param MorticianAddress $address
     * @return Mortician
     */
    public function setAddress(MorticianAddress $address = null)
    {
        $this->address = $address;
        $address->setMortician($this);

        return $this;
    }

    /**
     * Get address
     *
     * @return MorticianAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add obituaries
     *
     * @param Obituary $obituaries
     * @return Mortician
     */
    public function addObituary(Obituary $obituaries)
    {
        $this->obituaries[] = $obituaries;

        return $this;
    }

    /**
     * Remove obituaries
     *
     * @param Obituary $obituaries
     */
    public function removeObituary(Obituary $obituaries)
    {
        $this->obituaries->removeElement($obituaries);
    }

    /**
     * Get obituaries
     *
     * @return Collection
     */
    public function getObituaries()
    {
        return $this->obituaries;
    }

    /**
     * Add morticians
     *
     * @param Mortician $morticians
     * @return Mortician
     */
    public function addMortician(Mortician $morticians)
    {
        $this->morticians[] = $morticians;

        return $this;
    }

    /**
     * Remove morticians
     *
     * @param Mortician $morticians
     */
    public function removeMortician(Mortician $morticians)
    {
        $this->morticians->removeElement($morticians);
    }

    /**
     * Get morticians
     *
     * @return Collection
     */
    public function getMorticians()
    {
        return $this->morticians;
    }

    /**
     * Set parentMortician
     *
     * @param Mortician $parentMortician
     * @return Mortician
     */
    public function setParentMortician(Mortician $parentMortician = null)
    {
        $this->parentMortician = $parentMortician;

        return $this;
    }

    /**
     * Get parentMortician
     *
     * @return Mortician
     */
    public function getParentMortician()
    {
        return $this->parentMortician;
    }

    /**
     * Add cemeteries
     *
     * @param Cemetery $cemeteries
     * @return Mortician
     */
    public function addCemetery(Cemetery $cemeteries)
    {
        $this->cemeteries[] = $cemeteries;

        return $this;
    }

    /**
     * Remove cemeteries
     *
     * @param Cemetery $cemeteries
     */
    public function removeCemetery(Cemetery $cemeteries)
    {
        $this->cemeteries->removeElement($cemeteries);
    }

    /**
     * Get cemeteries
     *
     * @return Collection
     */
    public function getCemeteries()
    {
        return $this->cemeteries;
    }

    /**
     * Add supplier
     *
     * @param Supplier $supplier
     * @return Mortician
     */
    public function addSupplier(Supplier $supplier)
    {
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param Supplier $supplier
     */
    public function removeSupplier(Supplier $supplier)
    {
        $this->supplier->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return Collection
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
     * @param MorticianMedia $medias
     * @return Mortician
     */
    public function addMedia(MorticianMedia $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param MorticianMedia $medias
     */
    public function removeMedia(MorticianMedia $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set logo
     *
     * @param Media $logo
     * @return Mortician
     */
    public function setLogo(Media $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return Media
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @return bool
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param bool $state
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

    /**
     * Add users
     *
     * @param \Aspetos\Model\Entity\MorticianUser $users
     * @return Mortician
     */
    public function addUser(\Aspetos\Model\Entity\MorticianUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Aspetos\Model\Entity\MorticianUser $users
     */
    public function removeUser(\Aspetos\Model\Entity\MorticianUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
