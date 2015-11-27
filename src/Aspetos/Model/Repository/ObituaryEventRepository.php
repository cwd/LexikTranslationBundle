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
 * ObituaryEvent Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class ObituaryEventRepository extends EntityRepository
{

    /**
     * @param array $search
     * @param array $exclude
     * @param bool  $getFutureEvents
     * @param int   $offset
     * @param int   $count
     * @return array
     */
    public function search($search = array(), $exclude = null, $getFutureEvents = true, $offset = 0, $count = 20)
    {
        $qb = $this->createQueryBuilder('event');
        $qb
            ->select(
                'event',
                'type'
            )
            ->leftJoin('event.obituaryEventType', 'type')
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('event.dateStart', 'ASC');

        if ($getFutureEvents) {
            $qb
                ->andWhere('event.dateStart >= :dateStart')
                ->setParameter('dateStart', new \DateTime());
        }

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
                ->andWhere('event.id NOT IN (:events)')
                ->setParameter('events', $exclude);
        }

        return $qb->getQuery()->getResult();
    }
}
