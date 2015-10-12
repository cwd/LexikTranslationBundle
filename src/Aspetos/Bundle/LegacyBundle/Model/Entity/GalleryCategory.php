<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_gallery_category",
 *     indexes={@ORM\Index(name="mid", columns={"gid"}),@ORM\Index(name="cid", columns={"cid"})}
 * )
 *
 */
class GalleryCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $gcid;
}
