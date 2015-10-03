<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ProductCategoryRepository")
 */
class ProductCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imageId;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductCategory", mappedBy="parentCategory")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductHasCategory", mappedBy="productCategory")
     */
    private $productHasCategory;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\ProductCategory", inversedBy="categories")
     * @ORM\JoinColumn(name="parentCategoryId", referencedColumnName="id")
     */
    private $parentCategory;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     */
    private $image;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productHasCategory = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ProductCategory
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
     * @return ProductCategory
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
     * Set imageId
     *
     * @param integer $imageId
     * @return ProductCategory
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * Get imageId
     *
     * @return integer 
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * Add categories
     *
     * @param \Aspetos\Model\Entity\ProductCategory $categories
     * @return ProductCategory
     */
    public function addCategory(\Aspetos\Model\Entity\ProductCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Aspetos\Model\Entity\ProductCategory $categories
     */
    public function removeCategory(\Aspetos\Model\Entity\ProductCategory $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add productHasCategory
     *
     * @param \Aspetos\Model\Entity\ProductHasCategory $productHasCategory
     * @return ProductCategory
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
     * Set parentCategory
     *
     * @param \Aspetos\Model\Entity\ProductCategory $parentCategory
     * @return ProductCategory
     */
    public function setParentCategory(\Aspetos\Model\Entity\ProductCategory $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \Aspetos\Model\Entity\ProductCategory 
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Set image
     *
     * @param \Aspetos\Model\Entity\Media $image
     * @return ProductCategory
     */
    public function setImage(\Aspetos\Model\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Aspetos\Model\Entity\Media 
     */
    public function getImage()
    {
        return $this->image;
    }
}
