<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_country",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="alpha2", columns={"alpha2"}),@ORM\UniqueConstraint(name="alpha3", columns={"alpha3"})}
 * )
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=3)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $alpha2;

    /**
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    private $alpha3;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $en;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $de;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $de_f;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $fr;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $active;
}