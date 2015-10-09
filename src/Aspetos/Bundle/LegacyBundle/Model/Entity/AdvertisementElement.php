<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_advertisementElement",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="advertisementId", columns={"advertisementId","name"})}
 * )
 *
 */
class AdvertisementElement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=8)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $content;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $paddingTop;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $paddingRight;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $paddingBottom;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $paddingLeft;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $border;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $size;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $originalWidth;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $originalHeight;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $markToDelete;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
