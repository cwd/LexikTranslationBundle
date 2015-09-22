<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_video_thumb",
 *     indexes={@ORM\Index(name="es_video_thumb_ibfk_1", columns={"mid"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="es_video_thumb_mid_time", columns={"mid","time"}),
 *         @ORM\UniqueConstraint(name="es_video_thumb_thumb_id_mid", columns={"thumb_id","mid"})
 *     }
 * )
 */
class VideoThumb
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $vtid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $time;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $thumb_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $name;
}