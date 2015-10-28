<?php
namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CompanyRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {"supplier"="Aspetos\Model\Entity\Supplier","mortician"="Aspetos\Model\Entity\Mortician"}
 * )
 */
class Company
{
    use Timestampable;
    use Blameable;

    // Make the discriminator accessible
    const TYPE_MORTICIAN = 'mortician';
    const TYPE_SUPPLIER  = 'supplier';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 250)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Length(groups={"default"}, max = 150)
     */
    protected $shortName;

    /**
     * @ORM\Column(type="string", unique=true, length=250, nullable=false)
     * @Gedmo\Slug(fields={"name"}))
     */
    protected $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="phone_number", length=30, nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    protected $phone;

    /**
     * @ORM\Column(type="phone_number", length=30, nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    protected $fax;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 150)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $webpage;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = "30", groups={"default"})
     */
    protected $vat;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = "30", groups={"default"})
     */
    protected $commercialRegNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $origId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $crmId;

    /**
     * @ORM\Column(type="string", length=2, nullable=false, options={"default":"AT"})
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, min = 2, max = 2)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(groups={"default"}, max = 200)
     */
    protected $contactName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $registeredAt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $state;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $partnerVienna;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="logoId", referencedColumnName="id")
     */
    protected $logo;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="avatarId", referencedColumnName="id")
     */
    protected $avatar;


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
     * @return Company
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
     * Set shortName
     *
     * @param string $shortName
     * @return Company
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
     * Set slug
     *
     * @param string $slug
     * @return Company
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
     * @return Company
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
     * @param phone_number $phone
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return phone_number
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param phone_number $fax
     * @return Company
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return phone_number
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Company
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
     * Set webpage
     *
     * @param string $webpage
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return  int
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
     * Set crmId
     *
     * @param integer $crmId
     * @return Company
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
     * Set country
     *
     * @param string $country
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Set state
     *
     * @param boolean $state
     * @return Company
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set partnerVienna
     *
     * @param boolean $partnerVienna
     * @return Company
     */
    public function setPartnerVienna($partnerVienna)
    {
        $this->partnerVienna = $partnerVienna;

        return $this;
    }

    /**
     * Get partnerVienna
     *
     * @return boolean
     */
    public function getPartnerVienna()
    {
        return $this->partnerVienna;
    }

    /**
     * Set logo
     *
     * @param \Aspetos\Model\Entity\Media $logo
     * @return Company
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
     * Set avatar
     *
     * @param \Aspetos\Model\Entity\Media $avatar
     * @return Company
     */
    public function setAvatar(\Aspetos\Model\Entity\Media $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
