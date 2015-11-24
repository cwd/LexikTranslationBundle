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
}
