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
class CemeteryRepository extends EntityRepository
{
    /**
     * @param string $country
     * @param int    $offset
     * @param int    $count
     * @return array
     */
    public function findByCountry($country, $offset = 0, $count = 20)
    {
        $qb = $this->createQueryBuilder('cemetery')
            ->select('cemetery', 'address', 'administration')
            ->join('cemetery.address', 'address')
            ->join('cemetery.administration', 'administration')
            ->where('address.country = :country')
            ->setParameter('country', $country)
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('cemetery.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
