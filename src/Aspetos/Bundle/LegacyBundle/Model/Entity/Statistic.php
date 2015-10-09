<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_statistic",
 *     indexes={
 *         @ORM\Index(name="MULTIPLE_TYPE_MEDA", columns={"type","area","foreignId"}),
 *         @ORM\Index(name="INDEX_TYPE", columns={"type"}),
 *         @ORM\Index(name="INDEX_AREA", columns={"area"}),
 *         @ORM\Index(name="INDEX_FOREIGN_ID", columns={"foreignId"})
 *     }
 * )
 *
 */
class Statistic
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $area;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $loggedInUid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $foreignId;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
