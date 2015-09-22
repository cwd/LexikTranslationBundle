<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_pdfEditorElement",
 *     indexes={
 *         @ORM\Index(name="pdfEditorId2es_pdfEditor_id", columns={"pdfEditorId"}),
 *         @ORM\Index(name="pdfEditorElement_type", columns={"type"}),
 *         @ORM\Index(name="pdfEditorElement_typeKey", columns={"typeKey"})
 *     }
 * )
 */
class PdfEditorElement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $elementId;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $type;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $typeKey;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $typeSort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $hide;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $content;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $xPos;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $yPos;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $sizeType;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $width;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $font;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $description;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}