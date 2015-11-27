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

    /**
     * @param Obituary $obituary
     * @return mixed
     */
    public function countByObituary(Obituary $obituary)
    {
        $qb = $this->createQueryBuilder('candle');
        $qb
            ->select($qb->expr()->count('candle'))
            ->andWhere('candle.obituary = :obituary')
            ->andWhere('candle.state = :state')
            ->setParameter('obituary', $obituary)
            ->setParameter('state', 'active');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
