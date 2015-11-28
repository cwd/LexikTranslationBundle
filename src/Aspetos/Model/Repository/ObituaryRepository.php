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
use Doctrine\ORM\Query\Expr\Join;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class ObituaryRepository extends BaseRepository
{
    /**
     * @param Mortician $mortician
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return int
     */
    public function getCountByMortician(Mortician $mortician, \DateTime $fromDate, \DateTime $toDate)
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('count(o)')
           ->where('o.mortician = :mortician')
           ->andWhere('o.hide=0')
           ->andWhere('o.createdAt >= :fromDate')
           ->andWhere('o.createdAt <= :toDate')
           ->setParameter('mortician', $mortician)
           ->setParameter('fromDate', $fromDate)
           ->setParameter('toDate', $toDate);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $search
     * @param array $exclude
     * @param int   $offset
     * @param int   $count
     * @param array $orderBy
     * @return array
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20, $orderBy = null)
    {

        $qb = $this->createQueryBuilder('obituary');
        $qb
            ->select(
                'obituary',
                'cemetery'
            )
            ->leftJoin('obituary.cemetery', 'cemetery')
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->addGroupBy('obituary.id')
            ->andWhere('obituary.hide = 0');

        $this->addSearch($qb, $search);
        $this->addOrderBy($qb, $orderBy, 'obituary.dayOfDeath', 'DESC');
        $this->addExcludes($qb, $exclude, 'obituary.id');

        return $qb->getQuery()->getResult();
    }
}
