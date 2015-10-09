<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_attribute",
 *     indexes={@ORM\Index(name="groupid", columns={"groupid"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uniqueGroupIdPrettyUrl", columns={"groupid","prettyUrl"})}
 * )
 *
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $attid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'radio'"})
     */
    private $type;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $doNotDelete;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $sort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $company;
}
