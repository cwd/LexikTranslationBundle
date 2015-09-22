<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_media_category",
 *     indexes={@ORM\Index(name="mid", columns={"mid"}),@ORM\Index(name="cid", columns={"cid"})}
 * )
 */
class MediaCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mcid;
}