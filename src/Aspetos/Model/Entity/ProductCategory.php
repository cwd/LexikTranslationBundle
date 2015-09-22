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
}