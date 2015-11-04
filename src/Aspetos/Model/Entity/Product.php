<?php

namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Type(
     *      type="numeric",
     *      message="The value {{ value }} is not numeric."
     * )
     */
    private $sellPrice = 0;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     * @Assert\Type(
     *      type="numeric",
     *      message="The value {{ value }} is not numeric."
     * )
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
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 250)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductHasCategory", mappedBy="product", cascade={"all"})
     */
    private $productHasCategory;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\BasePrice", mappedBy="product")
     */
    private $basePrices;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="product")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media", inversedBy="product")
     * @ORM\JoinColumn(name="mainImageId", referencedColumnName="id", nullable=false)
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
        $this->productHasCategory = new ArrayCollection();
        $this->medias = new ArrayCollection();
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
     * Get assigned product categories.
     *
     * @return ProductCategory[]
     */
    public function getCategories()
    {
        $categories = array_map(function(ProductHasCategory $pc) {
            return $pc->getProductCategory();
        }, $this->productHasCategory->toArray());

        return $categories;
    }

    /**
     * Set product categories.
     *
     * @param array $categories
     * @return Product
     */
    public function setCategories($categories)
    {
        $this->productHasCategory = new ArrayCollection();

        foreach ($categories as $category) {
            $productHasCategory = new ProductHasCategory();
            $productHasCategory
                ->setProductCategory($category)
                ->setProduct($this);

            $this->addProductHasCategory($productHasCategory);
        }

        return $this;
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
}
