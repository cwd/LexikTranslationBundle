<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_statistic_agg_userDead",
 *     indexes={
 *         @ORM\Index(name="quantityCandle", columns={"quantityCandle"}),
 *         @ORM\Index(name="quantityCondolence", columns={"quantityCondolence"}),
 *         @ORM\Index(name="quantityViewDetail", columns={"quantityViewDetail"}),
 *         @ORM\Index(name="period", columns={"period"}),
 *         @ORM\Index(name="INDEX_YEAR", columns={"year"}),
 *         @ORM\Index(name="MULTIPLE_YEAR_PERIOD_UID", columns={"year","uid","period"}),
 *         @ORM\Index(name="FULLTEXT_MORTICIAN", columns={"mortician"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_YEAR_PERIOD", columns={"year","period","uid"})}
 * )
 */
class StatisticAggUserDead
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $year;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $period;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $quantityCandle;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $quantityCandleOrder;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $quantityCondolence;

    /**
     * @ORM\Column(type="integer", length=8, nullable=true, options={"default":"NULL"})
     */
    private $quantityViewDetail;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $mortician;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $registerDate;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}