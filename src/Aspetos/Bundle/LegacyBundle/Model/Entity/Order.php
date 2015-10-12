<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_order",
 *     indexes={
 *         @ORM\Index(name="uidAgent", columns={"uidAgent"}),
 *         @ORM\Index(name="uidPrincipal", columns={"uidPrincipal"}),
 *         @ORM\Index(name="uidDeceased", columns={"uidDeceased"})
 *     }
 * )
 *
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     */
    private $orderId;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $billingName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $billingStreet;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $billingPlace;
}
