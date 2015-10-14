<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Cemetery as Entity;
use Aspetos\Service\Legacy\Exception\ObituaryNotFoundException as NotFoundException;
use Monolog\Logger;

/**
 * Class Aspetos Service Cemetery
 *
 * @package Aspetos\Service\Legacy
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.cemetery", parent="cwd.generic.service.generic")
 */
class CemeteryService extends Generic
{

    /**
     * @param EntityManager $legacyEntityManager
     * @param Logger        $logger
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $legacyEntityManager, Logger $logger)
    {
        parent::__construct($legacyEntityManager, $logger);
    }

    /**
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function find($pid)
    {
        try {
            $obj = parent::findById('Legacy:Cemetery', intval($pid));

            if ($obj === null) {
                $this->getLogger()->info('Row with ID {id} not found', array('id' => $pid));
                throw new NotFoundException('Row with ID ' . $pid . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }

    /**
     * @param int $amount
     * @param int $offset
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAll($amount = 10000, $offset = 0)
    {
        return $this->findAllByModel('Legacy:Cemetery', array(), array(), $amount, $offset);
    }
}
