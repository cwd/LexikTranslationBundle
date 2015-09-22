<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_attributeName", indexes={@ORM\Index(name="attid", columns={"attid"})})
 */
class AttributeName
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $attnid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $valueName;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false, options={"default":"'0'"})
     */
    private $sort;
}