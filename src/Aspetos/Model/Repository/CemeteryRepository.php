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
     * @param array $search
     * @param array $exclude
     * @param int   $offset
     * @param int   $count
     * @return array
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20)
    {
        $qb = $this->createQueryBuilder('cemetery')
            ->select('cemetery', 'address', 'administration')
            ->join('cemetery.address', 'address')
            ->join('cemetery.administration', 'administration')
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('cemetery.name', 'ASC');

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
                ->andWhere('cemetery.id NOT IN (:cemeteries)')
                ->setParameter('cemeteries', $exclude);
        }

        return $qb->getQuery()->getResult();
    }
}
