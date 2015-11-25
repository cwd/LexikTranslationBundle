<?php

namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ProductCategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 * @Gedmo\Tree(type="nested")
 */
class ProductCategory
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
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 200)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\TreeLeft
     */
    private $lft;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\TreeLevel
     */
    private $lvl;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Gedmo\TreeRight
     */
    private $rgt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeRoot
     */
    private $root;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductCategory", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductHasCategory", mappedBy="productCategory")
     */
    private $productHasCategory;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="imageId", referencedColumnName="id", nullable=false)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\ProductCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="id")
     * @Gedmo\TreeParent
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\SupplierType", inversedBy="productCategories")
     * @ORM\JoinColumn(name="supplierTypeId", referencedColumnName="id")
     */
    private $supplierType;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Returns name prefixed with "-" for tree display
     * @return string
     */
    public function getTreename()
    {
        return str_repeat('- ', $this->getLvl()).' '.$this->getName();
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

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return ProductCategory
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return ProductCategory
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return ProductCategory
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return ProductCategory
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Add children
     *
     * @param \Aspetos\Model\Entity\ProductCategory $children
     * @return ProductCategory
     */
    public function addChild(\Aspetos\Model\Entity\ProductCategory $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Aspetos\Model\Entity\ProductCategory $children
     */
    public function removeChild(\Aspetos\Model\Entity\ProductCategory $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Aspetos\Model\Entity\ProductCategory $parent
     * @return ProductCategory
     */
    public function setParent(\Aspetos\Model\Entity\ProductCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Aspetos\Model\Entity\ProductCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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
}
