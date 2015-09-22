<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_advertisement", indexes={@ORM\Index(name="uid", columns={"uid"})})
 */
class Advertisement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $uidOwner;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $publisherId;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    private $publisherName;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $sample;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $typeId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $typeName;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $layoutId;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $appearance;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $column;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $width;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $dpi;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $netPriceList;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $issues;

    /**
     * @ORM\Column(type="integer", length=8, nullable=true, options={"default":"NULL"})
     */
    private $run;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $issueDiscount;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $issueDiscountValue;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $netPriceBase;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $promotionDiscount;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $promotionDiscountValue;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $netPriceBaseAfterPromotion;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $taxAd;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $taxAdValue;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $netPrice;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $vat;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $vatValue;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $grossPrice;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $grossPriceNoPromotion;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $changeColumns;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $font;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"'100'"})
     */
    private $fontsize;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $ownFilename;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $ownFilenameUploaded;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $pdfGenerated;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $history;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}