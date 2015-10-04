<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Handler;

use Aspetos\Service\HandlerInterface;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Service\CemeteryService;
use Monolog\Logger;
use Aspetos\Service\Event\CemeteryEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Aspetos Service Cemetery
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.handler.cemetery", parent="cwd.generic.service.generic")
 */
class CemeteryHandler extends Generic implements HandlerInterface
{
    /**
     * @var cemeteryService
     */
    protected $cemeteryService;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EntityManager            $entityManager
     * @param Logger                   $logger
     * @param CemeteryService          $cemeteryService
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *     "cemeteryService" = @DI\Inject("aspetos.service.cemetery"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EntityManager $entityManager, Logger $logger, CemeteryService $cemeteryService, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($entityManager, $logger);
        $this->cemeteryService = $cemeteryService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Cemetery $cemetery
     *
     * @return Cemetery
     */
    public function remove($cemetery)
    {
        $this->entityManager->remove($cemetery);

        $this->eventDispatcher->dispatch('aspetos.event.cemetery.remove.pre', new CemeteryEvent($cemetery));
        $this->cemeteryService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.cemetery.remove.post', new CemeteryEvent($cemetery));

        return $cemetery;
    }

    /**
     * @param Cemetery $cemetery
     *
     * @return Cemetery
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function create($cemetery)
    {
        $this->cemeteryService->persist($cemetery);

        $this->eventDispatcher->dispatch('aspetos.event.cemetery.create.pre', new CemeteryEvent($cemetery));
        $this->cemeteryService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.cemetery.create.post', new CemeteryEvent($cemetery));

        return $cemetery;
    }

    /**
     * @param Cemetery $cemetery
     *
     * @return Cemetery
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function edit($cemetery)
    {
        $this->eventDispatcher->dispatch('aspetos.event.cemetery.edit.pre', new CemeteryEvent($cemetery));
        $this->cemeteryService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.cemetery.edit.post', new CemeteryEvent($cemetery));

        return $cemetery;
    }

    /**
     * @return CemeteryService
     */
    public function getCemeteryService()
    {
        return $this->cemeteryService;
    }

}
