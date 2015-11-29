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
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\QueryBuilder;

/**
 * Base Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class BaseRepository extends EntityRepository
{
    /**
     * @param QueryBuilder $qb
     * @param array             $search
     */
    protected function addSearch(QueryBuilder $qb, $search)
    {
        foreach ($search as $key => $value) {
            $paramName = 'param_' . md5($key);

            if (is_array($value)) {
                $qb->andWhere("$key IN (:$paramName)");
            } else {
                $qb->andWhere("$key = :$paramName");
            }

            $qb->setParameter($paramName, $value);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array             $orderBy
     * @param null               $defaultOrderBy
     * @param string           $defaultDirection
     */
    protected function addOrderBy(QueryBuilder $qb, $orderBy = array(), $defaultOrderBy = null, $defaultDirection = 'ASC')
    {
        if (empty($orderBy)) {
            if ($defaultOrderBy !== null) {
                $qb->orderBy($defaultOrderBy, $defaultDirection);
            }
        } else {
            foreach ($orderBy as $field => $direction) {
                $qb->orderBy($field, $direction);
            }
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array             $excludes
     * @param string            $field
     */
    protected function addExcludes(QueryBuilder $qb, $excludes, $field)
    {
        if (!empty($excludes)) {
            $qb
                ->andWhere($field . ' NOT IN (:excludes)')
                ->setParameter('excludes', $excludes);
        }
    }
}
