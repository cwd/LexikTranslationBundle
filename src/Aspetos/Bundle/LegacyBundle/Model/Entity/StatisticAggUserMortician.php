<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Bundle\LegacyBundle\Model\Repository\StatisticAggUserMorticianRepository")
 * @ORM\Table(
 *     name="es_statistic_agg_userMortician",
 *     indexes={
 *         @ORM\Index(name="quantityCandle", columns={"quantityCandle"}),
 *         @ORM\Index(name="quantityCondolence", columns={"quantityCondolence"}),
 *         @ORM\Index(name="quantityViewDetail", columns={"quantityViewDetail"}),
 *         @ORM\Index(name="period", columns={"period"}),
 *         @ORM\Index(name="quantityDeadUser", columns={"quantityDeadUser"}),
 *         @ORM\Index(name="uid2uidMortician", columns={"uid"}),
 *         @ORM\Index(name="INDEX_YEAR", columns={"year"}),
 *         @ORM\Index(name="quantityCandleOrder", columns={"quantityCandleOrder"}),
 *         @ORM\Index(name="domain", columns={"domain"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_YEAR_PERIOD", columns={"year","period","uid"})}
 * )
 */
class StatisticAggUserMortician
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $domain;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $period;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $quantityDeadUser;

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
     * @ORM\Column(type="datetime", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return mixed
     */
    public function getQuantityDeadUser()
    {
        return $this->quantityDeadUser;
    }

    /**
     * @return mixed
     */
    public function getQuantityCandle()
    {
        return $this->quantityCandle;
    }

    /**
     * @return mixed
     */
    public function getQuantityCandleOrder()
    {
        return $this->quantityCandleOrder;
    }

    /**
     * @return mixed
     */
    public function getQuantityCondolence()
    {
        return $this->quantityCondolence;
    }

    /**
     * @return mixed
     */
    public function getQuantityViewDetail()
    {
        return $this->quantityViewDetail;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }


}
