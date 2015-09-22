<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_opengeodb_city",
 *     indexes={@ORM\Index(name="state_id", columns={"state_id"}),@ORM\Index(name="county_id", columns={"county_id"})}
 * )
 */
class OpengeodbCity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $state_id;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $county_id;

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