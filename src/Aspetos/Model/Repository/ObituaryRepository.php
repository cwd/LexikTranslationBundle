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
     * @return array
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20)
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
            ->orderBy('obituary.dayOfDeath', 'DESC')
            ->andWhere('obituary.hide = 0');

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
