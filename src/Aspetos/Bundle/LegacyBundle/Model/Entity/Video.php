<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_video")
 *
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mid;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $original_width;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $original_height;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $main_width;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $main_height;
}
