<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierRepository")
 */
class Supplier
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crmId;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\SupplierAddress", mappedBy="supplier")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="parentSupplier")
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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\SupplierType", inversedBy="supplier")
     * @ORM\JoinTable(
     *     name="SupplierHasType",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierTypeId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplierType;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="supplier")
     */
    private $cemetery;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="supplier")
     */
    private $mortician;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basePrices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->obituaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplierType = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cemetery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mortician = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Aspetos\Model\Entity\SupplierAddress $address
     * @return Supplier
     */
    public function setAddress(\Aspetos\Model\Entity\SupplierAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Aspetos\Model\Entity\SupplierAddress 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add suppliers
     *
     * @param \Aspetos\Model\Entity\Supplier $suppliers
     * @return Supplier
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
     * Add product
     *
     * @param \Aspetos\Model\Entity\Product $product
     * @return Supplier
     */
    public function addProduct(\Aspetos\Model\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Aspetos\Model\Entity\Product $product
     */
    public function removeProduct(\Aspetos\Model\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add basePrices
     *
     * @param \Aspetos\Model\Entity\BasePrice $basePrices
     * @return Supplier
     */
    public function addBasePrice(\Aspetos\Model\Entity\BasePrice $basePrices)
    {
        $this->basePrices[] = $basePrices;

        return $this;
    }

    /**
     * Remove basePrices
     *
     * @param \Aspetos\Model\Entity\BasePrice $basePrices
     */
    public function removeBasePrice(\Aspetos\Model\Entity\BasePrice $basePrices)
    {
        $this->basePrices->removeElement($basePrices);
    }

    /**
     * Get basePrices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBasePrices()
    {
        return $this->basePrices;
    }

    /**
     * Set parentSupplier
     *
     * @param \Aspetos\Model\Entity\Supplier $parentSupplier
     * @return Supplier
     */
    public function setParentSupplier(\Aspetos\Model\Entity\Supplier $parentSupplier = null)
    {
        $this->parentSupplier = $parentSupplier;

        return $this;
    }

    /**
     * Get parentSupplier
     *
     * @return \Aspetos\Model\Entity\Supplier 
     */
    public function getParentSupplier()
    {
        return $this->parentSupplier;
    }

    /**
     * Add obituaries
     *
     * @param \Aspetos\Model\Entity\Obituary $obituaries
     * @return Supplier
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
     * Add supplierType
     *
     * @param \Aspetos\Model\Entity\SupplierType $supplierType
     * @return Supplier
     */
    public function addSupplierType(\Aspetos\Model\Entity\SupplierType $supplierType)
    {
        $this->supplierType[] = $supplierType;

        return $this;
    }

    /**
     * Remove supplierType
     *
     * @param \Aspetos\Model\Entity\SupplierType $supplierType
     */
    public function removeSupplierType(\Aspetos\Model\Entity\SupplierType $supplierType)
    {
        $this->supplierType->removeElement($supplierType);
    }

    /**
     * Get supplierType
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierType()
    {
        return $this->supplierType;
    }

    /**
     * Add cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     * @return Supplier
     */
    public function addCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery[] = $cemetery;

        return $this;
    }

    /**
     * Remove cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     */
    public function removeCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery->removeElement($cemetery);
    }

    /**
     * Get cemetery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }

    /**
     * Add mortician
     *
     * @param \Aspetos\Model\Entity\Mortician $mortician
     * @return Supplier
     */
    public function addMortician(\Aspetos\Model\Entity\Mortician $mortician)
    {
        $this->mortician[] = $mortician;

        return $this;
    }

    /**
     * Remove mortician
     *
     * @param \Aspetos\Model\Entity\Mortician $mortician
     */
    public function removeMortician(\Aspetos\Model\Entity\Mortician $mortician)
    {
        $this->mortician->removeElement($mortician);
    }

    /**
     * Get mortician
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMortician()
    {
        return $this->mortician;
    }
}
