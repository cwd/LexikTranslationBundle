<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
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
}