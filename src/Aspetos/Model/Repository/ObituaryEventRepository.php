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
class ObituaryEventRepository extends BaseRepository
{

    /**
     * @param array $search
     * @param array $exclude
     * @param bool  $getFutureEvents
     * @param int   $offset
     * @param int   $count
     * @param array $orderBy
     * @return array
     */
    public function search($search = array(), $exclude = null, $getFutureEvents = true, $offset = 0, $count = 20, $orderBy = null)
    {
        $qb = $this->createQueryBuilder('event');
        $qb
            ->select(
                'event',
                'type',
                'obituary'
            )
            ->leftJoin('event.obituaryEventType', 'type')
            ->leftJoin('event.obituary', 'obituary')
            ->setMaxResults($count)
            ->setFirstResult($offset);

        if ($getFutureEvents) {
            $qb
                ->andWhere('event.dateStart >= :dateStart')
                ->setParameter('dateStart', new \DateTime());
        }

        $this->addSearch($qb, $search);
        $this->addOrderBy($qb, $orderBy, 'event.dateStart');
        $this->addExcludes($qb, $exclude, 'event.id');

        return $qb->getQuery()->getResult();
    }
}
