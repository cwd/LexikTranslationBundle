<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_media_rating",
 *     indexes={@ORM\Index(name="uid", columns={"uid"}),@ORM\Index(name="foreignId", columns={"foreignId"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="type", columns={"type","foreignId","uid"})}
 * )
 *
 */
class MediaRating
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $rid;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $foreignId;

    /**
     * @ORM\Column(type="decimal", length=1, nullable=true, scale=0, options={"default":"NULL"})
     */
    private $rating;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $comment;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $response;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $timestamp;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $mid;
}
