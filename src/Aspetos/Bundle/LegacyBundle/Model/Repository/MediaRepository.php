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
 * Media Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class MediaRepository extends EntityRepository
{
    /**
     * @param int $galleryId
     *
     * @return array
     */
    public function findMedia($galleryId)
    {
        $sql = 'SELECT dn.deathnoticeType, m.filename, m.mid, m.description
                FROM es_media m
                LEFT JOIN es_deathnotice dn ON m.mid = dn.mid
                WHERE m.gid = :galleryId
                AND dn.archiv != 1 AND m.status = "complete"';

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('deathnoticeType', 'deathnoticeType')
            ->addScalarResult('filename', 'filename')
            ->addScalarResult('mid', 'mid')
            ->addScalarResult('description', 'description');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('galleryId', $galleryId);

        return $query->getResult(Query::HYDRATE_ARRAY);
    }
}
