<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CustomerOrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class CustomerOrder
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
     * @ORM\Column(type="string", nullable=false, options={"default":"new"})
     */
    private $state;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deliveredAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $paymentReference;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=6, scale=2)
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $customerId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\OrderItem", mappedBy="order")
     */
    private $orderItems;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Customer", inversedBy="orders")
     * 
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id")
     */
    private $obituary;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderItems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set state
     *
     * @param string $state
     * @return CustomerOrder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set payedAt
     *
     * @param \DateTime $payedAt
     * @return CustomerOrder
     */
    public function setPayedAt($payedAt)
    {
        $this->payedAt = $payedAt;

        return $this;
    }

    /**
     * Get payedAt
     *
     * @return \DateTime
     */
    public function getPayedAt()
    {
        return $this->payedAt;
    }

    /**
     * Set deliveredAt
     *
     * @param \DateTime $deliveredAt
     * @return CustomerOrder
     */
    public function setDeliveredAt($deliveredAt)
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    /**
     * Get deliveredAt
     *
     * @return \DateTime
     */
    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }

    /**
     * Set paymentReference
     *
     * @param string $paymentReference
     * @return CustomerOrder
     */
    public function setPaymentReference($paymentReference)
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }

    /**
     * Get paymentReference
     *
     * @return string
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }

    /**
     * Set totalAmount
     *
     * @param string $totalAmount
     * @return CustomerOrder
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Add orderItem
     *
     * @param \Aspetos\Model\Entity\OrderItem $orderItem
     * @return CustomerOrder
     */
    public function addOrderItem(\Aspetos\Model\Entity\OrderItem $orderItem)
    {
        $this->orderItems[] = $orderItem;
        $this->updateTotalAmount();

        return $this;
    }

    /**
     * Remove orderItem
     *
     * @param \Aspetos\Model\Entity\OrderItem $orderItem
     * @return CustomerOrder
     */
    public function removeOrderItem(\Aspetos\Model\Entity\OrderItem $orderItem)
    {
        $this->orderItems->removeElement($orderItem);
        $this->updateTotalAmount();

        return $this;
    }

    /**
     * Get orderItems
     *
     * @return \Doctrine\Common\Collections\Collection|OrderItem[]
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * Set customer
     *
     * @param \Aspetos\Model\Entity\Customer $customer
     * @return CustomerOrder
     */
    public function setCustomer(\Aspetos\Model\Entity\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Aspetos\Model\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
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
     * Update total amount by adding up all assigned order items.
     *
     * @return self
     */
    public function updateTotalAmount()
    {
        $totalAmount = 0.0;
        foreach ($this->getOrderItems() as $orderItem) {
            $orderItem->updatePrice();
            $totalAmount += $orderItem->getPrice();
        }

        return $this->setTotalAmount($totalAmount);
    }

    /**
     * Add the given product to this order using the given amount.
     * This will check for an existing OrderItem for the same product and update accordingly.
     *
     * All items with an amount of 0 or less will be removed during post-cleanup.
     *
     * @param Product $product
     * @param int     $amount
     *
     * @return self
     */
    public function addProduct(Product $product, $amount = 1)
    {
        $orderItem = new OrderItem();
        $orderItem
            ->setProduct($product)
            ->setAmount($amount);

        return $this->mergeOrderItem($orderItem);
    }

    /**
     * Add the given orderItem, checking for existing OrderItems.
     * This will check for an existing OrderItem for the same product and update accordingly.
     *
     * All items with an amount of 0 or less will be removed during post-cleanup.
     *
     * @param OrderItem $orderItem
     *
     * @return self
     */
    public function mergeOrderItem(OrderItem $orderItem)
    {
        $existingItem = $this->getItemForProduct($orderItem->getProduct());
        if (null === $existingItem) {
            $this->addOrderItem($orderItem);
        } else {
            $existingItem->addAmount($orderItem->getAmount());
        }

        $this->cleanupOrderItems();
        $this->updateTotalAmount();

        return $this;
    }

    /**
     * Clean up order items, removing all items with an amount of 0 or less.
     *
     * @return self
     */
    public function cleanupOrderItems()
    {
        foreach ($this->getOrderItems() as $orderItem) {
            if ($orderItem->getAmount() <= 0) {
                $this->removeOrderItem($orderItem);
            }
        }

        return $this;
    }

    /**
     * Get OrderItem for the given Product if it already exists in this CustomerOrder
     *
     * @param Product $product
     *
     * @return OrderItem|null
     */
    protected function getItemForProduct(Product $product)
    {
        foreach ($this->getOrderItems() as $item) {
            if ($item->getProduct() == $product) {
                return $item;
            }
        }
    }

    /**
     * Get number of items, adding up orderItem amounts.
     *
     * @return int
     */
    public function getNumberOfItems()
    {
        $amount = 0;
        foreach ($this->getOrderItems() as $orderItem) {
            $amount += $orderItem->getAmount();
        }

        return $amount;
    }

    /**
     * Get number of order items, grouped by product.
     *
     * @return int
     */
    public function getNumberOfPositions()
    {
        return count($this->getOrderItems());
    }
}
