<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CustomerRepository")
 */
class Customer extends BaseUser
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aspetos\Model\Entity\CustomerAddress",
     *     mappedBy="customer",
     *     cascade={"persist","remove"}
     * )
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\CustomerOrder", mappedBy="customer")
     */
    private $orders;

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_CUSTOMER;
    }

    /**
     * Add addresses
     *
     * @param \Aspetos\Model\Entity\CustomerAddress $addresses
     * @return Customer
     */
    public function addAddress(\Aspetos\Model\Entity\CustomerAddress $addresses)
    {
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \Aspetos\Model\Entity\CustomerAddress $addresses
     */
    public function removeAddress(\Aspetos\Model\Entity\CustomerAddress $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add orders
     *
     * @param \Aspetos\Model\Entity\CustomerOrder $orders
     * @return Customer
     */
    public function addOrder(\Aspetos\Model\Entity\CustomerOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Aspetos\Model\Entity\CustomerOrder $orders
     */
    public function removeOrder(\Aspetos\Model\Entity\CustomerOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
