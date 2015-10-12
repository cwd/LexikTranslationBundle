<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_gallery",
 *     indexes={@ORM\Index(name="es_gallery_ibfk_1", columns={"uid"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uid_gid_sort", columns={"uid","gtype","sort"})}
 * )
 *
 */
class Gallery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $gid;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $gtype;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $sort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $hide;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2, options={"default":"'1.00'"})
     */
    private $priceFactor;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
