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
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Permission Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class PermissionRepository extends EntityRepository
{
    /**
     * @param array  $fields
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAsArray(array $fields = array(), $sort = 'p.name', $order = 'asc')
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select($fields)
           ->addOrderBy($sort, $order);

        return $qb->getQuery()
                  ->useQueryCache(true)
                  ->useResultCache(true)
                  ->getResult(Query::HYDRATE_ARRAY);
    }
}
