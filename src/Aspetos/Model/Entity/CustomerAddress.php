<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CustomerAddressRepository")
 */
class CustomerAddress extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="`default`", options={"default":0})
     */
    private $default;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"invoice"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Customer", inversedBy="addresses", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="customerId", referencedColumnName="id", nullable=false)
     */
    private $customer;

    /**
     * Set title
     *
     * @param string $title
     * @return CustomerAddress
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set default
     *
     * @param boolean $default
     * @return CustomerAddress
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean 
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return CustomerAddress
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set customer
     *
     * @param \Aspetos\Model\Entity\Customer $customer
     * @return CustomerAddress
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
