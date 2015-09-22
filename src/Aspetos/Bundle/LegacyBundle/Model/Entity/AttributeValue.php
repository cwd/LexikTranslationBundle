<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_attributeValue",
 *     indexes={@ORM\Index(name="attnid", columns={"attnid"}),@ORM\Index(name="uid", columns={"uid"})}
 * )
 */
class AttributeValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=8)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $attvid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $value;
}