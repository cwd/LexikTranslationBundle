<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_advertisement2area",
 *     indexes={@ORM\Index(name="advertisementId", columns={"advertisementId","areaId"})}
 * )
 */
class Advertisement2Area
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $areaId;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $issue;

    /**
     * @ORM\Column(type="integer", length=8, nullable=false)
     */
    private $run;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $netPriceList;
}