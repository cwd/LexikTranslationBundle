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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Media", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="SupplierHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mediaId", referencedColumnName="id", nullable=false)}
     * )
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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="supplier")
     */
    private $mortician;

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
        $this->suppliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basePrices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->obituaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplierTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cemeteries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mortician = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add medias
     *
     * @param \Aspetos\Model\Entity\Media $medias
     * @return Supplier
     */
    public function addMedia(\Aspetos\Model\Entity\Media $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Aspetos\Model\Entity\Media $medias
     */
    public function removeMedia(\Aspetos\Model\Entity\Media $medias)
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
     * Add supplierTypes
     *
     * @param \Aspetos\Model\Entity\SupplierType $supplierTypes
     * @return Supplier
     */
    public function addSupplierType(\Aspetos\Model\Entity\SupplierType $supplierTypes)
    {
        $this->supplierTypes[] = $supplierTypes;

        return $this;
    }

    /**
     * Remove supplierTypes
     *
     * @param \Aspetos\Model\Entity\SupplierType $supplierTypes
     */
    public function removeSupplierType(\Aspetos\Model\Entity\SupplierType $supplierTypes)
    {
        $this->supplierTypes->removeElement($supplierTypes);
    }

    /**
     * Get supplierTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplierTypes()
    {
        return $this->supplierTypes;
    }

    /**
     * Add cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     * @return Supplier
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
