<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MediaRepository")
 */
class Media extends \Cwd\MediaBundle\Model\Entity\Media
{
    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Product", mappedBy="mainImage", cascade={"persist"})
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add product
     *
     * @param \Aspetos\Model\Entity\Product $product
     * @return Media
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
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
