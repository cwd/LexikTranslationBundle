<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_category", uniqueConstraints={@ORM\UniqueConstraint(name="category", columns={"category"})})
 *
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'user'"})
     */
    private $type;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'gallery'"})
     */
    private $area;
}
