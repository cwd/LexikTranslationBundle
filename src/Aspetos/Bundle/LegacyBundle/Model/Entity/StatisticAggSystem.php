<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_statistic_agg_system",
 *     indexes={
 *         @ORM\Index(name="INDEX_PERIOD", columns={"period"}),
 *         @ORM\Index(name="INDEX_USERCATEGORY", columns={"userCategory"}),
 *         @ORM\Index(name="INDEX_YEAR", columns={"year"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_YEAR_PERIOD", columns={"year","period","userCategory"})}
 * )
 *
 */
class StatisticAggSystem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $userCategory;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $period;

    /**
     * @ORM\Column(type="integer", length=8, nullable=false)
     */
    private $valuePeriod;

    /**
     * @ORM\Column(type="integer", length=8, nullable=false)
     */
    private $valueEntire;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
