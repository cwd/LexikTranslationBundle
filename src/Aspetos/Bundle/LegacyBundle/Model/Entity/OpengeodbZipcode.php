<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_opengeodb_zipcode",
 *     indexes={
 *         @ORM\Index(name="city_id", columns={"city_id"}),
 *         @ORM\Index(name="district_id", columns={"district_id"}),
 *         @ORM\Index(name="zipcode", columns={"zipcode"})
 *     }
 * )
 */
class OpengeodbZipcode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $zipcode;
}