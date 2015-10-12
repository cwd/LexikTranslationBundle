<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_favorite",
 *     indexes={@ORM\Index(name="favUid", columns={"favUid"}),@ORM\Index(name="es_favorite_ibfk_1", columns={"uid"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uid", columns={"uid","favUid"})}
 * )
 *
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $fid;
}
