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

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class CondolenceRepository extends EntityRepository
{
    /**
     * @param Mortician $mortician
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param string    $state
     *
     * @return mixed
     */
    public function getCountByMortician(Mortician $mortician, \DateTime $fromDate, \DateTime $toDate, $state='active')
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
}
