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

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class MorticianRepository extends BaseRepository
{
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
        $qb = $this->createQueryBuilder('mortician')
            ->select('mortician', 'address', 'logo', 'avatar')
            ->join('mortician.address', 'address')
            ->leftJoin('mortician.logo', 'logo')
            ->leftJoin('mortician.avatar', 'avatar')
            ->setMaxResults($count)
            ->setFirstResult($offset);

        $this->addSearch($qb, $search);
        $this->addOrderBy($qb, $orderBy, 'mortician.name');
        $this->addExcludes($qb, $exclude, 'mortician.id');

        return $qb->getQuery()->getResult();
    }
}
