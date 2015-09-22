<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_sa_district", indexes={@ORM\Index(name="provinceId", columns={"provinceId"})})
 */
class SaDistrict
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     */
    private $districtCode;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $provinceId;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $province;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $provinceCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $district;
}