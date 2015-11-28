<?php
/*
 * This file is part of aspetos
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Model\Repository;

use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\Candle;
use Aspetos\Model\Entity\Obituary;
use Cwd\GenericBundle\Doctrine\EntityRepository;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")se
 */
class CandleRepository extends EntityRepository
{
    /**
     * @param Mortician $mortician
     * @param bool      $paid
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param string    $state
     *
     * @return int|null
     */
    public function getCountByMortician(Mortician $mortician, $paid, \DateTime $fromDate, \DateTime $toDate, $state='active')
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c)')
            ->leftJoin('c.obituary', 'o')
            ->where('o.mortician = :mortician')
            ->andWhere('o.hide=0')
            ->andWhere('c.state=:state')
            ->andWhere('c.createdAt >= :fromDate')
            ->andWhere('c.createdAt <= :toDate')
            ->setParameter('mortician', $mortician)
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->setParameter('state', $state);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Obituary       $obituary
     * @param null|string    $state
     * @param null|\DateTime $fromDate
     * @param null|\DateTime $toDate
     *
     * @return int|null
     */
    public function getCountByObituary(Obituary $obituary, $state = null, $fromDate = null, $toDate = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('count(c)')
           ->where('c.obituary = :obituary')
           ->setParameter('obituary', $obituary);

        if ($state !== null) {
            $qb->andWhere('c.state = :state')
               ->setParameter('state', $state);
        }

        if ($fromDate !== null) {
            $qb->andWhere('c.createdAt >= :fromDate')
               ->setParameter('fromDate', $fromDate);
        }

        if ($toDate !== null) {
            $qb->andWhere('c.createdAt <= :toDate')
               ->setParameter('toDate', $toDate);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $search
     * @param array $exclude
     * @param bool  $getInactive
     * @param int   $offset
     * @param int   $count
     * @return array
     */
    public function search($search = array(), $exclude = null, $getInactive = false, $offset = 0, $count = 20)
    {

        $qb = $this->createQueryBuilder('candle');
        $qb
            ->select(
                'candle',
                'product'
            )
            ->leftJoin('candle.product', 'product')
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('candle.createdAt', 'DESC')
            ->andWhere('candle.state = :state')
            ->setParameter(':state', 'active');

        if ($getInactive === false) {
            $orX = $qb->expr()->orX(
                $qb->expr()->andX(
                    $qb->expr()->gt('product.lifeTime', ':lifeTime'),
                    $qb->expr()->gte('candle.expiresAt', ':paidCandleMaxVisibleDate')
                ),
                $qb->expr()->gte('candle.expiresAt', ':now')
            );
            $qb
                ->setParameter('lifeTime', Candle::FREE_CANDLE_MAX_LIFETIME)
                ->setParameter('now', new \DateTime())
                ->setParameter('paidCandleMaxVisibleDate', new \DateTime('-' . Candle::PAID_CANDLE_MAX_DAY_OFFSET . 'days'))
                ->andWhere($orX);
        }

        foreach ($search as $key => $value) {
            $paramName = strtolower(str_replace('.', '', $key));

            if (is_array($value)) {
                $qb->andWhere("$key IN (:$paramName)");
            } else {
                $qb->andWhere("$key = :$paramName");
            }

            $qb->setParameter($paramName, $value);
        }

        if ($exclude !== null) {
            $qb
                ->andWhere('candle.id NOT IN (:candles)')
                ->setParameter('candles', $exclude);
        }

        return $qb->getQuery()->getResult();
    }
}
