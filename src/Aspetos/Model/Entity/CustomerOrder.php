<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
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
}