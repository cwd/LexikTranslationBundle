<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_province",
 *     indexes={@ORM\Index(name="countryId", columns={"countryId"}),@ORM\Index(name="countryId2country", columns={"countryId"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="prettyUrl", columns={"prettyUrl"})}
 * )
 */
class Province
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=3, options={"default":"'0'"})
     */
    private $provinceId;

    /**
     * @ORM\Column(type="string", length=50, nullable=false, options={"default":""})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $nameShort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, options={"default":"NULL"})
     */
    private $de_id1;

    /**
     * @ORM\Column(type="integer")
     */
    private $countryId;
}
