<?php
namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Supplier
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
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Url(groups={"default"})
     * @Assert\Length(max = "200", groups={"default"})
     */
    private $webpage;

    /**
     *
     * @Assert\Email(groups={"default"})
     * @Assert\Length(max = "75", groups={"default"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = "30", groups={"default"})
     */
    private $vat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crmId;

    /**
     * @ORM\Column(type="string", unique=true, length=250, nullable=false)
     * @Assert\Length(groups={"default"}, max = 200)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 250)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\SupplierAddress", mappedBy="supplier", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="parentSupplier", cascade={"persist"})
     */
    private $suppliers;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Product", mappedBy="supplier")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\BasePrice", mappedBy="supplier")
     */
    private $basePrices;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="suppliers")
     * @ORM\JoinColumn(name="parentSupplierId", referencedColumnName="id")
     */
    private $parentSupplier;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="suppliers", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="ObituaryHasSupplier",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $obituaries;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\SupplierType", inversedBy="suppliers", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="SupplierHasType",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierTypeId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplierTypes;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="suppliers", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="SupplierHasCemetery",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)}
     * )
     *
     */
    private $cemeteries;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="supplier")
     */
    private $mortician;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->suppliers = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->basePrices = new ArrayCollection();
        $this->obituaries = new ArrayCollection();
        $this->supplierTypes = new ArrayCollection();
        $this->cemeteries = new ArrayCollection();
        $this->mortician = new ArrayCollection();
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
     * Set phone
     *
     * @param string $phone
     * @return Supplier
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
     * @return Supplier
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
     * @return Supplier
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
     * Set email
     *
     * @param string $email
     * @return Supplier
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
     * Set vat
     *
     * @param string $vat
     * @return Supplier
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Supplier
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
     * Set crmId
     *
     * @param integer $crmId
     * @return Supplier
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
     * @param SupplierAddress $address
     * @return Supplier
     */
    public function setAddress(SupplierAddress $address = null)
    {
        $this->address = $address;
        $address->setSupplier($this);

        return $this;
    }

    /**
     * Get address
     *
     * @return SupplierAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add suppliers
     *
     * @param Supplier $suppliers
     * @return Supplier
     */
    public function addSupplier(Supplier $suppliers)
    {
        $this->suppliers[] = $suppliers;

        return $this;
    }

    /**
     * Remove suppliers
     *
     * @param Supplier $suppliers
     */
    public function removeSupplier(Supplier $suppliers)
    {
        $this->suppliers->removeElement($suppliers);
    }

    /**
     * Get suppliers
     *
     * @return Collection
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }

    /**
     * Add product
     *
     * @param Product $product
     * @return Supplier
     */
    public function addProduct(Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add basePrices
     *
     * @param BasePrice $basePrices
     * @return Supplier
     */
    public function addBasePrice(BasePrice $basePrices)
    {
        $this->basePrices[] = $basePrices;

        return $this;
    }

    /**
     * Remove basePrices
     *
     * @param BasePrice $basePrices
     */
    public function removeBasePrice(BasePrice $basePrices)
    {
        $this->basePrices->removeElement($basePrices);
    }

    /**
     * Get basePrices
     *
     * @return Collection
     */
    public function getBasePrices()
    {
        return $this->basePrices;
    }

    /**
     * Set parentSupplier
     *
     * @param Supplier $parentSupplier
     * @return Supplier
     */
    public function setParentSupplier(Supplier $parentSupplier = null)
    {
        $this->parentSupplier = $parentSupplier;

        return $this;
    }

    /**
     * Get parentSupplier
     *
     * @return Supplier
     */
    public function getParentSupplier()
    {
        return $this->parentSupplier;
    }

    /**
     * Add obituaries
     *
     * @param Obituary $obituaries
     * @return Supplier
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
     * Add supplierType
     *
     * @param SupplierType $supplierType
     * @return Supplier
     */
    public function addSupplierType(SupplierType $supplierType)
    {
        $this->supplierTypes[] = $supplierType;
        $supplierType->addSupplier($this);

        return $this;
    }

    /**
     * Remove supplierType
     *
     * @param SupplierType $supplierType
     */
    public function removeSupplierType(SupplierType $supplierType)
    {
        $this->supplierTypes->removeElement($supplierType);
    }

    /**
     * Get supplierType
     *
     * @return Collection
     */
    public function getSupplierTypes()
    {
        return $this->supplierTypes;
    }

    /**
     * Add cemetery
     *
     * @param Cemetery $cemetery
     * @return Supplier
     */
    public function addCemetery(Cemetery $cemetery)
    {
        $cemetery->addSupplier($this);
        $this->cemeteries[] = $cemetery;

        return $this;
    }

    /**
     * Remove cemetery
     *
     * @param Cemetery $cemetery
     */
    public function removeCemetery(Cemetery $cemetery)
    {
        $this->cemeteries->removeElement($cemetery);
    }

    /**
     * Get cemetery
     *
     * @return Collection
     */
    public function getCemeteries()
    {
        return $this->cemeteries;
    }

    /**
     * Add mortician
     *
     * @param Mortician $mortician
     * @return Supplier
     */
    public function addMortician(Mortician $mortician)
    {
        $this->mortician[] = $mortician;
        $mortician->addSupplier($this);

        return $this;
    }

    /**
     * Remove mortician
     *
     * @param Mortician $mortician
     */
    public function removeMortician(Mortician $mortician)
    {
        $this->mortician->removeElement($mortician);
    }

    /**
     * Get mortician
     *
     * @return Collection
     */
    public function getMortician()
    {
        return $this->mortician;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * get formatted name for select in backend
     * @return string
     */
    public function formattedName()
    {
        return sprintf('%s (%s-%s %s)', $this->getName(), $this->getAddress()->getCountry(), $this->getAddress()->getZipcode(), $this->getAddress()->getCity());
    }
}
