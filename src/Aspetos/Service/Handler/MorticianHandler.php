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
use Aspetos\Model\Entity\Mortician;
use Aspetos\Service\MorticianService;
use Psr\Log\LoggerInterface;
use Aspetos\Service\Event\MorticianEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Aspetos Service Mortician
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.handler.mortician", parent="cwd.generic.service.generic")
 */
class MorticianHandler extends Generic implements HandlerInterface
{
    /**
     * @var morticianService
     */
    protected $morticianService;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EntityManager            $entityManager
     * @param Logger                   $logger
     * @param MorticianService         $morticianService
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *     "morticianService" = @DI\Inject("aspetos.service.mortician"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger, MorticianService $morticianService, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($entityManager, $logger);
        $this->morticianService = $morticianService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Mortician $mortician
     *
     * @return Mortician
     */
    public function remove($mortician)
    {
        $this->entityManager->remove($mortician);

        $this->eventDispatcher->dispatch('aspetos.event.mortician.remove.pre', new MorticianEvent($mortician));
        $this->morticianService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.mortician.remove.post', new MorticianEvent($mortician));

        return $mortician;
    }

    /**
     * @param Mortician $mortician
     * @param bool      $flush
     *
     * @return Mortician
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function create($mortician, $flush = true)
    {
        $this->morticianService->persist($mortician);

        $this->eventDispatcher->dispatch('aspetos.event.mortician.create.pre', new MorticianEvent($mortician));

        if ($flush) {
            $this->morticianService->flush();
            $this->eventDispatcher->dispatch('aspetos.event.mortician.create.post', new MorticianEvent($mortician));
        }

        return $mortician;
    }

    /**
     * @param Mortician $mortician
     * @param bool      $flush
     *
     * @return Mortician
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function edit($mortician, $flush = true)
    {
        $this->eventDispatcher->dispatch('aspetos.event.mortician.edit.pre', new MorticianEvent($mortician));

        if ($flush) {
            $this->morticianService->flush();
            $this->eventDispatcher->dispatch('aspetos.event.mortician.edit.post', new MorticianEvent($mortician));
        }

        return $mortician;
    }

    /**
     * @return MorticianService
     */
    public function getMorticianService()
    {
        return $this->morticianService;
    }

}
