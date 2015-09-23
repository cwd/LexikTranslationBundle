<?php
/*
 * This file is part of aspetos.
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Model\Repository;

use Cwd\GenericBundle\Doctrine\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class UserRepository extends EntityRepository
{
    /**
     * Generate Static values as array
     * @param string $type
     * @param string $country
     * @param string $group
     *
     * @return array()
     */
    public function getStatistic($type = 'dead', $country = 'at', $group = 'month')
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count', 'count');
        $rsm->addScalarResult('signup', 'signup');
        $rsm->addScalarResult('dateYear', 'year');
        $rsm->addScalarResult('dateMonth', 'month');

        switch ($group) {
            case 'day':
                $sql = 'SELECT count(uid) as count, DATE(u.registerDate) as signup, MONTH(u.registerDate) as dateMonth, YEAR(u.registerDate) as dateYear FROM es_user u
                        WHERE
                            u.block=0 AND
                            u.userCategory=:type AND
                            u.domain IN (:country) AND
                            u.registerDate > :date
                        GROUP By DATE(u.registerDate)
                        ORDER By u.registerDate ASC';
                $date = new \DateTime('-6 month');
                break;
            case 'month':
                $sql = 'SELECT count(uid) as count, MONTH(u.registerDate) as signup, MONTH(u.registerDate) as dateMonth, YEAR(u.registerDate) as dateYear FROM es_user u
                        WHERE
                            u.registerDate is not null AND
                            u.block=0 AND
                            u.userCategory=:type AND
                            u.domain IN (:country) AND
                            u.registerDate > :date
                        GROUP By MONTH(u.registerDate), YEAR(u.registerDate)
                        ORDER By u.registerDate ASC
                        ';
                $date = new \DateTime('-2 year');
                break;
            case 'quarter':
                $sql = 'SELECT count(uid) as count, QUARTER(u.registerDate) as signup, MONTH(u.registerDate) as dateMonth, YEAR(u.registerDate) as dateYear FROM es_user u
                        WHERE
                            u.registerDate is not null AND
                            u.block=0 AND
                            u.userCategory=:type AND
                            u.domain IN (:country) AND
                            u.registerDate > :date
                        GROUP By QUARTER(u.registerDate), YEAR(u.registerDate)
                        ORDER By u.registerDate ASC
                        ';
                $date = new \DateTime('-5 year');
                break;
        }


        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('type', $type)
              ->setParameter('country', $country == 'all' ? array('at','de') : array($country))
              ->setParameter('date', $date);

        return $query->getResult(Query::HYDRATE_ARRAY);
    }
}
