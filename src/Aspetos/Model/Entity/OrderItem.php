<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\OrderItemRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

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

    /**
     * Initialize OrderItem object.
     */
    public function __construct()
    {
        $this->amount = 0;
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
     * Set amount
     *
     * @param integer $amount
     * @return OrderItem
     */
    public function setAmount($amount)
    {
        $this->amount = (int) $amount;

        return $this;
    }

    /**
     * Add the given amount to the existing value.
     *
     * @param int $amount
     * @return self
     */
    public function addAmount($amount)
    {
        return $this->setAmount($this->getAmount() + $amount);
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return OrderItem
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
     * Set order
     *
     * @param \Aspetos\Model\Entity\CustomerOrder $order
     * @return OrderItem
     */
    public function setOrder(\Aspetos\Model\Entity\CustomerOrder $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Aspetos\Model\Entity\CustomerOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \Aspetos\Model\Entity\Product $product
     * @return OrderItem
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
     * Update price by calculating amount x product selling price. If no product is assigned the price will be 0.
     *
     * @return self
     */
    public function updatePrice()
    {
        $price = 0.0;
        if (null !== $this->getProduct()) {
            $price = $this->getProduct()->getSellPrice() * $this->getAmount();
        }

        return $this->setPrice($price);
    }
}
