<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_opengeodb_district", indexes={@ORM\Index(name="city_id", columns={"city_id"})})
 */
class OpengeodbDistrict
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $lng;
}