<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ProductHasCategoryRepository")
 */
class ProductHasCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Product", inversedBy="productHasCategory")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\ProductCategory", inversedBy="productHasCategory")
     * @ORM\JoinColumn(name="productCategoryId", referencedColumnName="id", nullable=false)
     */
    private $productCategory;
}