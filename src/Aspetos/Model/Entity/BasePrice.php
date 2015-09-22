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
}