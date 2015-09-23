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

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\User;
use Aspetos\Service\UserService;
use Monolog\Logger;
use Aspetos\Service\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Aspetos Service User
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.handler.user", parent="cwd.generic.service.generic")
 */
class UserHandler extends Generic
{
    /**
     * @var userService
     */
    protected $userService;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EntityManager            $entityManager
     * @param Logger                   $logger
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @DI\InjectParams({
     *     "userService" = @DI\Inject("aspetos.service.user"),
     *     "eventDispatcher" = @DI\Inject("event_dispatcher")
     * })
     */
    public function __construct(EntityManager $entityManager, Logger $logger, UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($entityManager, $logger);
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function removeUser(User $user)
    {
        $user->setDeletedAt(new \DateTime());

        $this->eventDispatcher->dispatch('aspetos.event.user.remove.pre', new UserEvent($user));
        $this->userService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.user.remove.post', new UserEvent($user));

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function createUser(User $user)
    {
        $this->userService->persist($user);

        $this->eventDispatcher->dispatch('aspetos.event.user.create.pre', new UserEvent($user));
        $this->userService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.user.create.post', new UserEvent($user));

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function editUser(User $user)
    {
        $this->eventDispatcher->dispatch('aspetos.event.user.edit.pre', new UserEvent($user));
        $this->userService->flush();
        $this->eventDispatcher->dispatch('aspetos.event.user.edit.post', new UserEvent($user));

        return $user;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }

}
