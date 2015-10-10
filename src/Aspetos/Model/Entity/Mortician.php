<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

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
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $fax;

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
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician")
     */
    private $obituaries;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="parentMortician")
     */
    private $morticians;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="morticians")
     * @ORM\JoinColumn(name="parentMorticianId", referencedColumnName="id")
     */
    private $parentMortician;

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
     * Set addresses
     *
     * @param \Aspetos\Model\Entity\MorticianAddress $addresses
     * @return Mortician
     */
    public function setAddresses(\Aspetos\Model\Entity\MorticianAddress $addresses = null)
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Get addresses
     *
     * @return \Aspetos\Model\Entity\MorticianAddress
     */
    public function getAddresses()
    {
        return $this->addresses;
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
}
