<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_deathnotice",
 *     indexes={
 *         @ORM\Index(name="es_deathnoticeMid2type", columns={"deathnoticeType","mid"}),
 *         @ORM\Index(name="es_deathnotice_deathnoticetype", columns={"deathnoticeType"})
 *     }
 * )
 *
 */
class Deathnotice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mid;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"'deathnoticeStandard'"})
     */
    private $deathnoticeType;

    /**
     * @ORM\Column(type="integer", length=2, nullable=true, options={"default":"'0'"})
     */
    private $archiv;
}
