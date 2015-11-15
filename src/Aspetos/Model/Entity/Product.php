<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Product
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
     * @ORM\Column(type="decimal", nullable=false, options={"default":0})
     */
    private $sellPrice;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     *
     */
    private $basePrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mainImageId;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lifeTime;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Aspetos\Model\Entity\ProductHasCategory",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"persist","remove"}
     * )
     */
    private $productHasCategory;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\BasePrice", mappedBy="product")
     */
    private $basePrices;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductAvailability", mappedBy="product", cascade={"persist"})
     */
    private $productAvailability;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="product")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media", inversedBy="product")
     */
    private $mainImage;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Media", mappedBy="products")
     */
    private $medias;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productHasCategory = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sellPrice
     *
     * @param string $sellPrice
     * @return Product
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return string
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set basePrice
     *
     * @param string $basePrice
     * @return Product
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * Get basePrice
     *
     * @return string
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * Set mainImageId
     *
     * @param integer $mainImageId
     * @return Product
     */
    public function setMainImageId($mainImageId)
    {
        $this->mainImageId = $mainImageId;

        return $this;
    }

    /**
     * Get mainImageId
     *
     * @return integer
     */
    public function getMainImageId()
    {
        return $this->mainImageId;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
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
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Product
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
     * Add productHasCategory
     *
     * @param \Aspetos\Model\Entity\ProductHasCategory $productHasCategory
     * @return Product
     */
    public function addProductHasCategory(\Aspetos\Model\Entity\ProductHasCategory $productHasCategory)
    {
        $this->productHasCategory[] = $productHasCategory;

        return $this;
    }

    /**
     * Remove productHasCategory
     *
     * @param \Aspetos\Model\Entity\ProductHasCategory $productHasCategory
     */
    public function removeProductHasCategory(\Aspetos\Model\Entity\ProductHasCategory $productHasCategory)
    {
        $this->productHasCategory->removeElement($productHasCategory);
    }

    /**
     * Get productHasCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductHasCategory()
    {
        return $this->productHasCategory;
    }

    /**
     * Set supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return Product
     */
    public function setSupplier(\Aspetos\Model\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return \Aspetos\Model\Entity\Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set mainImage
     *
     * @param \Aspetos\Model\Entity\Media $mainImage
     * @return Product
     */
    public function setMainImage(\Aspetos\Model\Entity\Media $mainImage = null)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Add medias
     *
     * @param \Aspetos\Model\Entity\Media $medias
     * @return Product
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
     * Set state
     *
     * @param boolean $state
     * @return Product
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
     * Add basePrices
     *
     * @param \Aspetos\Model\Entity\BasePrice $basePrices
     * @return Product
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
     * Add productAvailability
     *
     * @param \Aspetos\Model\Entity\ProductAvailability $productAvailability
     * @return Product
     */
    public function addProductAvailability(\Aspetos\Model\Entity\ProductAvailability $productAvailability)
    {
        $this->productAvailability[] = $productAvailability;

        return $this;
    }

    /**
     * Remove productAvailability
     *
     * @param \Aspetos\Model\Entity\ProductAvailability $productAvailability
     */
    public function removeProductAvailability(\Aspetos\Model\Entity\ProductAvailability $productAvailability)
    {
        $this->productAvailability->removeElement($productAvailability);
    }

    /**
     * Get productAvailability
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductAvailability()
    {
        return $this->productAvailability;
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
     * @return mixed
     */
    public function getLifeTime()
    {
        return $this->lifeTime;
    }

    /**
     * @param mixed $lifeTime
     *
     * @return $this
     */
    public function setLifeTime($lifeTime)
    {
        $this->lifeTime = $lifeTime;

        return $this;
    }
}

