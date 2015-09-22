<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Media extends \Cwd\MediaBundle\Model\Entity\Media
{
    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Product", mappedBy="mainImage")
     */
    private $product;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Product", inversedBy="medias")
     * @ORM\JoinTable(
     *     name="ProductHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="Media_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $products;
}