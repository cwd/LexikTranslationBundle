<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CustomerOrderRepository")
 */
class CustomerOrder
{
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
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\OrderItem", mappedBy="order")
     */
    private $orderItems;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="customerId", referencedColumnName="id", nullable=false)
     */
    private $customer;
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
     * Add orderItems
     *
     * @param \Aspetos\Model\Entity\OrderItem $orderItems
     * @return CustomerOrder
     */
    public function addOrderItem(\Aspetos\Model\Entity\OrderItem $orderItems)
    {
        $this->orderItems[] = $orderItems;

        return $this;
    }

    /**
     * Remove orderItems
     *
     * @param \Aspetos\Model\Entity\OrderItem $orderItems
     */
    public function removeOrderItem(\Aspetos\Model\Entity\OrderItem $orderItems)
    {
        $this->orderItems->removeElement($orderItems);
    }

    /**
     * Get orderItems
     *
     * @return \Doctrine\Common\Collections\Collection 
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
}
