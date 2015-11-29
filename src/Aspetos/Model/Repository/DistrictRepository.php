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
 * District Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class DistrictRepository extends EntityRepository
{
    /**
     * @param string $country
     * @return array
     */
    public function findByCountry($country)
    {
        $qb = $this->createQueryBuilder('d')
            ->select(array('d', 'r'))
            ->join('d.region', 'r')
            ->orderBy('r.name', 'ASC')
            ->addOrderBy('d.name', 'ASC')
            ->where('r.country = :country')
            ->setParameter('country', $country);

        return $qb->getQuery()->getResult();
    }
    /**
     * @param Region $region
     * @return array
     */
    public function findByRegion(Region $region)
    {
        $qb = $this->createQueryBuilder('district')
            ->select(
                'district'
            )
            ->join('district.region', 'region')
            ->orderBy('district.name', 'ASC')
            ->where('district.region = :region')
            ->setParameter('region', $region);

        return $qb->getQuery()->getResult();
    }
}
