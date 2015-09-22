<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_shop_order",
 *     indexes={
 *         @ORM\Index(name="uid", columns={"uid"}),
 *         @ORM\Index(name="payid", columns={"payId"}),
 *         @ORM\Index(name="entity", columns={"entity"}),
 *         @ORM\Index(name="deliveryGender", columns={"deliveryGender","deliveryCountryId"})
 *     }
 * )
 */
class ShopOrder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $oid;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'default'"})
     */
    private $entity;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $uid;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $status;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'outstanding'"})
     */
    private $statusPayment;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $statusHistory;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"'0.00'"})
     */
    private $sumGrossProducts;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"'0.00'"})
     */
    private $discount;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"'0.00'"})
     */
    private $shippingGross;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"'0'"})
     */
    private $shippingGrossVat;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"'0.00'"})
     */
    private $sumGross;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"'0.00'"})
     */
    private $sumVat;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"'0000-00-00 00:00:00'"})
     */
    private $created;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $oPaymentMethodId;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $oPaymentMethodName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $oServicePaymentID;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $oPayId;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $ip;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"NULL"})
     */
    private $hasUser;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $place;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $countryId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $comment;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"NULL"})
     */
    private $deliveryAddressEnabled;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $deliveryGender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $deliveryName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $deliveryForename;

    /**
     * @ORM\Column(type="string", length=500, nullable=true, options={"default":"NULL"})
     */
    private $deliveryStreet;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $deliveryZip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $deliveryPlace;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $deliveryCountryId;
}