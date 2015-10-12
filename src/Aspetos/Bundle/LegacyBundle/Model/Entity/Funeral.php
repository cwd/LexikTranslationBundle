<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_funeral",
 *     indexes={@ORM\Index(name="uidPlace", columns={"uidPlace"}),@ORM\Index(name="uid", columns={"uid"})}
 * )
 *
 */
class Funeral
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $funeralId;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;
}
