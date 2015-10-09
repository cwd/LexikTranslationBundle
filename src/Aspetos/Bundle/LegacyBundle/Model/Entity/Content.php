<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_content", indexes={@ORM\Index(name="menuid", columns={"menuid"})})
 *
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $contentid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, options={"default":"'main'"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $header;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $imageWidth;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $imageHeight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $imageAlt;

    /**
     * @ORM\Column(type="string", length=400, nullable=true, options={"default":"NULL"})
     */
    private $link;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
