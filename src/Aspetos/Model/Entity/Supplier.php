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
class Supplier extends Company
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\SupplierAddress", mappedBy="supplier", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\SupplierUser", mappedBy="supplier")
     */
    private $users;

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
     *
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\SupplierMedia", mappedBy="supplier", cascade={"persist"})
     */
    private $medias;

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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="suppliers")
     */
    private $morticians;

    /**
     * get formatted name for select in backend
     * @return string
     */
    public function formattedName()
    {
        return sprintf('%s (%s-%s %s)', $this->getName(), $this->getAddress()->getCountry(), $this->getAddress()->getZipcode(), $this->getAddress()->getCity());
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_SUPPLIER;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->suppliers = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->basePrices = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->obituaries = new ArrayCollection();
        $this->supplierTypes = new ArrayCollection();
        $this->cemeteries = new ArrayCollection();
        $this->morticians = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Only used in choice fields
     * @return string
     */
    public function getFormatedName()
    {
        return $this->name.'; '.$this->getAddress()->getZipcode().' '.$this->getAddress()->getCity();
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
     * Add supplierTypes
     *
     * @param SupplierType $supplierTypes
     * @return Supplier
     */
    public function addSupplierType(SupplierType $supplierTypes)
    {
        if (!$this->supplierTypes->contains($supplierTypes)) {
            $this->supplierTypes[] = $supplierTypes;
        }

        return $this;
    }

    /**
     * Remove supplierTypes
     *
     * @param SupplierType $supplierTypes
     */
    public function removeSupplierType(SupplierType $supplierTypes)
    {
        $this->supplierTypes->removeElement($supplierTypes);
    }

    /**
     * Get supplierTypes
     *
     * @return Collection
     */
    public function getSupplierTypes()
    {
        return $this->supplierTypes;
    }

    /**
     * Add cemeteries
     *
     * @param Cemetery $cemeteries
     * @return Supplier
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
     * Add mortician
     *
     * @param Mortician $mortician
     * @return Supplier
     */
    public function addMortician(Mortician $mortician)
    {
        if (!$this->morticians->contains($mortician)) {
            $mortician->addSupplier($this);
            $this->morticians[] = $mortician;
        }

        return $this;
    }

    /**
     * Remove mortician
     *
     * @param Mortician $mortician
     */
    public function removeMortician(Mortician $mortician)
    {
        $this->morticians->removeElement($mortician);
    }

    /**
     * Get mortician
     *
     * @return Collection
     */
    public function getMorticians()
    {
        return $this->morticians;
    }

    /**
     * Add users
     *
     * @param SupplierUser $users
     * @return Supplier
     */
    public function addUser(SupplierUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param SupplierUser $users
     */
    public function removeUser(SupplierUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add medias
     *
     * @param SupplierMedia $medias
     * @return Supplier
     */
    public function addMedia(SupplierMedia $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param SupplierMedia $medias
     */
    public function removeMedia(SupplierMedia $medias)
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
}
