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
     * @param string $country
     * @param array  $districts
     * @param array  $excludeIds
     * @param int    $offset
     * @param int    $count
     * @return array
     */
    public function findByCountryAndDistricts($country, $districts = null, $excludeIds = null, $offset = 0, $count = 20)
    {
        $qb = $this->createQueryBuilder('supplier')
            ->select('supplier', 'address', 'logo', 'avatar')
            ->join('supplier.address', 'address')
            ->leftJoin('supplier.logo', 'logo')
            ->leftJoin('supplier.avatar', 'avatar')
            ->where('address.country = :country')
            ->setParameter('country', $country)
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('supplier.name', 'ASC');

        if ($districts !== null) {
            $qb
                ->andWhere('address.district IN (:districts)')
                ->setParameter('districts', $districts);
        }

        if ($excludeIds !== null) {
            $qb
                ->andWhere('supplier.id NOT IN (:suppliers)')
                ->setParameter('suppliers', $excludeIds);
        }

        return $qb->getQuery()->getResult();
    }
}
