<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_sa_place", indexes={@ORM\Index(name="zip", columns={"zip"})})
 */
class SaPlace
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     */
    private $placeId;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $townCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $townName;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $townIndex;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $placeName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $zip;
}