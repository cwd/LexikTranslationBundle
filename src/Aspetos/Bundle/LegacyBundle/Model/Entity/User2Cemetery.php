<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_user2es_cemetery",
 *     indexes={@ORM\Index(name="uid", columns={"uid","cemId"}),@ORM\Index(name="cemId", columns={"cemId"})}
 * )
 *
 */
class User2Cemetery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}
