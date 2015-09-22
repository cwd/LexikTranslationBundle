<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_advertisementType")
 */
class AdvertisementType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $publisherId;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'portrait'"})
     */
    private $format;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $column;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $width;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $sort;
}