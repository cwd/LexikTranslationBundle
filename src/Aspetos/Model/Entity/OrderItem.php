<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false, options={"default":0})
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", nullable=false, precision=5, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\CustomerOrder", inversedBy="orderItems")
     * @ORM\JoinColumn(name="orderId", referencedColumnName="id", nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Product")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=false)
     */
    private $product;
}