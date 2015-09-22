<?php
/*
 * This file is part of aspetos.
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Model\Repository;

use Cwd\GenericBundle\Doctrine\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class StatisticAggUserMorticianRepository extends EntityRepository
{
    /**
     * Generate Static values as array
     * @param string $type
     * @param string $country
     *
     * @return array()
     */
    public function getStatistic($type = 'quantityCandle', $country = 'at')
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $rsm->addScalarResult('monthDate', 'month');
        $rsm->addScalarResult('yearDate', 'year');

        $sql = 'SELECT SUM(u.'.$type.') as count, REPLACE(period, "month", "") as monthDate, year as yearDate FROM es_statistic_agg_userMortician u
                WHERE
                    u.domain IN (:country) AND
                    year > 2013 AND
                    u.period != "currentYear"
                GROUP By REPLACE(period, "month", ""), year
                ORDER By u.id ASC
                ';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('type', $type)
            ->setParameter('country', $country == 'all' ? array('at','de') : array($country));

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Get Period of Statistics
     * @return array
     */
    public function getPeriod()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('m', 'month');
        $rsm->addScalarResult('year', 'year');

        $sql = "SELECT year, CAST(REPLACE(period, 'month', '') as unsigned) as m FROM es_statistic_agg_userDead
                where period != 'currentYear'
                AND year > 2012
                group by year, m
                order by year, m";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        dump($result);

        return $result;
    }

    /**
     * @param int    $year
     * @param string $month
     * @param string $orderBy
     * @param string $orderDir
     * @param int    $offset
     * @param int    $count
     *
     * @return array
     */
    public function getData($year = null, $month = null, $orderBy = 's.quantityDeadUser', $orderDir = 'DESC', $offset = 0, $count = 20)
    {
        if ($year === null) {
            $year = date('Y');
        }
        if ($month === null) {
            $month = 'month'.date('n');
        }

        $qb = $this->createQueryBuilder('s');
        $qb->select(array('s', 'u'))
           ->leftJoin('s.user', 'u')
           ->where('s.period=:month')
           ->andWhere('s.year=:year')
           ->orderBy($orderBy, $orderDir)
           ->setMaxResults($count)
           ->setFirstResult($offset)
           ->setParameter('month', $month)
           ->setParameter('year', $year);

        return $qb->getQuery()->getResult();
    }
}
