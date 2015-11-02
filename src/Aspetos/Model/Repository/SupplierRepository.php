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
     * @return array
     */
    public function findAllActiveAsArray()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select(array('s', 'a', 'd', 't'))
           ->leftJoin('s.address', 'a')
           ->leftJoin('a.district', 'd')
           ->leftJoin('s.supplierTypes', 't')
           ->where('s.state = :state')
           ->orderBy('s.name', 'ASC')
           ->setParameter('state', 'active');

        return $qb->getQuery()->getArrayResult();
    }
}
