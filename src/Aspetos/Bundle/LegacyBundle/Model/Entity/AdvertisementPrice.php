<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_advertisementPrice")
 *
 */
class AdvertisementPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $publisherId;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $run;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'normal'"})
     */
    private $style;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $s1_2;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $s1_4;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $s1_8;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $s1_16;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $issue;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2)
     */
    private $discount;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $sort;
}
