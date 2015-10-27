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
class AttributeRepository extends EntityRepository
{

    /**
     * @param int $uid
     *
     * @return array
     */
    public function getTypeByUid($uid)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('attId', 'attId');

        $sql = 'SELECT a.attId FROM es_attributeValue v
LEFT JOIN es_attribute a ON a.attid=v.attnId
LEFT JOIN es_attributeName n ON n.attId=a.attId
WHERE v.value=1
AND v.uid = :uid';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('uid', $uid);

        return $query->getResult(Query::HYDRATE_ARRAY);
    }
}
