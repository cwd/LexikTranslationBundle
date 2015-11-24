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

use Cwd\GenericBundle\Doctrine\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class ObituaryRepository extends EntityRepository
{
    /**
     * @param array $search
     * @param array $exclude
     * @param int   $offset
     * @param int   $count
     * @return array
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20)
    {

        $qb = $this->createQueryBuilder('obituary');
        $qb
            ->select(
                'obituary AS obituary_item',
                'cemetery',
                'COUNT(DISTINCT candles.id) AS candle_count',
                'COUNT(DISTINCT condolences.id) AS condolence_count'
            )
            ->leftJoin('obituary.cemetery', 'cemetery')
            ->leftJoin(
                'obituary.candles',
                'candles',
                Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('obituary.id', 'candles.obituary'),
                    $qb->expr()->eq('candles.state', ':state')
                )
            )
            ->leftJoin(
                'obituary.condolences',
                'condolences',
                Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('obituary.id', 'condolences.obituary'),
                    $qb->expr()->eq('condolences.state', ':state')
                )
            )
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->addGroupBy('obituary.id')
            ->orderBy('obituary.dayOfDeath', 'DESC')
            ->andWhere('obituary.hide = 0')
            ->setParameter(':state', 'active');

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
                ->andWhere('obituary.id NOT IN (:obituaries)')
                ->setParameter('obituaries', $exclude);
        }

        return $qb->getQuery()->getResult();
    }
}
