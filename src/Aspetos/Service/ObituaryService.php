<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\Obituary as Entity;
use Aspetos\Service\Exception\ObituaryNotFoundException as NotFoundException;
use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Obituary
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.obituary", parent="cwd.generic.service.generic")
 */
class ObituaryService extends Generic
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param EntityManager $entityManager
     * @param Logger        $logger
     * @param TokenStorage  $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, Logger $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($entityManager, $logger);
        $this->tokenStorage  = $tokenStorage;
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
            $obj = parent::findById('Model:Obituary', intval($pid));

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
     * @param int $uid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findByUid($uid)
    {
        try {
            $obj = $this->findOneByFilter('Model:Obituary', array('origId' => $uid));

            if ($obj === null) {
                throw new NotFoundException('Row with UID '.$uid.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException($e->getMessage());
        }
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
