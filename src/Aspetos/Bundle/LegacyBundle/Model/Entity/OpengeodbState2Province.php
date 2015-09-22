<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_opengeodb_state2es_province",
 *     indexes={@ORM\Index(name="provinceId", columns={"provinceId"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="opengeo", columns={"state_id","provinceId"})}
 * )
 */
class OpengeodbState2Province
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}