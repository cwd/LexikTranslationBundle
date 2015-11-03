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

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class SupplierRepository extends EntityRepository
{
    /**
     * @param string $query
     *
     * @return array
     */
    public function findAllActiveAsArray($query = '%')
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select(array('s', 'a', 'd', 't'))
           ->leftJoin('s.address', 'a')
           ->leftJoin('a.district', 'd')
           ->leftJoin('s.supplierTypes', 't')
           ->where('s.state = :state')
           ->andWhere(
               $qb->expr()->orX(
                   $qb->expr()->like('s.name', ':q'),
                   $qb->expr()->like('s.email', ':q'),
                   $qb->expr()->like('s.contactName', ':q'),
                   $qb->expr()->like('a.zipcode', ':q'),
                   $qb->expr()->like('a.street', ':q'),
                   $qb->expr()->like('a.street2', ':q'),
                   $qb->expr()->like('a.city', ':q'),
                   $qb->expr()->like('d.name', ':q')
               )
           )
           ->orderBy('s.name', 'ASC')
           ->setParameter('state', 'active')
           ->setParameter('q', '%'.$query.'%');

        return $qb->getQuery()->getArrayResult();
    }
}
