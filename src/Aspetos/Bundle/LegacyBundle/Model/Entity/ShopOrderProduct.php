<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_shop_order_product",
 *     indexes={@ORM\Index(name="pid", columns={"pid"}),@ORM\Index(name="oid", columns={"oid"})}
 * )
 *
 */
class ShopOrderProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $opid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=false, scale=2)
     */
    private $priceSingleGross;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2, options={"default":"'0.00'"})
     */
    private $discount;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=false, scale=2, options={"default":"'0.00'"})
     */
    private $donationGross;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $donationGrossVat;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'0'"})
     */
    private $vat;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\Column(type="date", nullable=false, options={"default":"'0000-00-00 00:00:00'"})
     */
    private $created;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"NULL"})
     */
    private $sentActivationEmailDate;
}
