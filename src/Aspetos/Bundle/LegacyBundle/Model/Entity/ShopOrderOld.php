<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_shop_order_old",
 *     indexes={
 *         @ORM\Index(name="uid", columns={"uid"}),
 *         @ORM\Index(name="payid", columns={"payid"}),
 *         @ORM\Index(name="pid", columns={"pid"})
 *     }
 * )
 *
 */
class ShopOrderOld
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $oid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $addedUid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $infoProductName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $infoPaymentName;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=false, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $donation;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $status;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"'0000-00-00 00:00:00'"})
     */
    private $addedDate;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"NULL"})
     */
    private $sentActivationEmailDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $viveumPID;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $viveumPaymentMethodId;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $viveumPaymentMethodName;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $isExecuted;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $resultCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $resultDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $servicePaymentID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $paymentStateDef;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $infoIP;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $infoGender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $infoName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $infoForename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $infoEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $infoStreet;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $infoZip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $infoPlace;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $infoCountryId;
}
