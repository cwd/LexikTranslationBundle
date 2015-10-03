<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\BasePriceRepository")
 */
class BasePrice
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
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Product", inversedBy="basePrice")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="basePrices")
     * @ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)
     */
    private $supplier;


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
     * Set price
     *
     * @param string $price
     * @return BasePrice
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product
     *
     * @param \Aspetos\Model\Entity\Product $product
     * @return BasePrice
     */
    public function setProduct(\Aspetos\Model\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Aspetos\Model\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return BasePrice
     */
    public function setSupplier(\Aspetos\Model\Entity\Supplier $supplier)
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
}
