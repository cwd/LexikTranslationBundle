<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_opengeodb_county2es_district",
 *     indexes={@ORM\Index(name="state_id", columns={"county_id"}),@ORM\Index(name="districtId", columns={"districtId"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="opengeo", columns={"county_id","districtId"})}
 * )
 *
 */
class OpengeodbCounty2District
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}
