<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ProductRepository")
 */
class Product
{
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
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\BasePrice", mappedBy="product")
     */
    private $basePrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mainImageId;

    /**
     * @ORM\Column(type="string", nullable=false)
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
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ProductHasCategory", mappedBy="product")
     */
    private $productHasCategory;

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
}