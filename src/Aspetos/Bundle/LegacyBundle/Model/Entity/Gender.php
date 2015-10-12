<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_gender")
 *
 */
class Gender
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":0})
     */
    private $person;
}
