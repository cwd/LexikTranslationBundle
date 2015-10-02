<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\Handler;

use Aspetos\Model\Entity\Admin;
use Aspetos\Service\Event\UserEvent;
use Aspetos\Service\Listener\UserPasswordListener;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\Handler\UserHandlerTest
 *
 * @package Aspetos\Tests\Service\Handler
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class UserHandlerTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Handler\UserHandler
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/../DataFixtures');
        $this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.handler.user');
    }

    public function testEntityManager()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->service->getEm());
    }

    public function testHasUserService()
    {
        $this->assertInstanceOf('Aspetos\Service\UserService', $this->service->getUserService());
    }

    public function testCreateUser()
    {
        $instance = $this;

        $user = new Admin();
        $user->setFirstname('foo')
             ->setLastname('bar')
             ->setEnabled(true)
             ->setLocked(false)
             ->setCreatedAt(new \DateTime())
             ->setEmail('foobar@host.at')
             ->setPlainPassword('asdf')
             ->setUpdatedAt(new \DateTime());

        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals(null, $user->getPassword());

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('aspetos.event.user.create.pre', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.create.pre', $name);
            $instance->assertEquals($user, $event->getUser());

            $listener = new UserPasswordListener();
            $listener->setPassword(new UserEvent($user));
        });


        $dispatcher->addListener('aspetos.event.user.create.post', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.create.post', $name);
            $instance->assertEquals($user, $event->getUser());
        });

        $this->service->create($user);

        $this->assertNotEquals(null, $user->getSalt());
        $this->assertNotEquals(null, $user->getPassword());
    }

    public function testEditUser()
    {
        $instance = $this;

        $user = $this->service->getUserService()->find(1);

        $lastname = $user->getLastname();
        $user->setLastname('somthing different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('aspetos.event.user.edit.pre', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.edit.pre', $name);
            $instance->assertEquals($user, $event->getUser());
        });
        $dispatcher->addListener('aspetos.event.user.edit.post', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.edit.post', $name);
            $instance->assertEquals($user, $event->getUser());
        });

        $this->service->edit($user);
        $this->assertNotEquals($lastname, $user->getLastname());
    }

    public function testremoveUser()
    {
        $instance = $this;

        $user = $this->service->getUserService()->find(1);

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('aspetos.event.user.remove.pre', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.remove.pre', $name);
            $instance->assertEquals($user, $event->getUser());
        });
        $dispatcher->addListener('aspetos.event.user.remove.post', function ($event, $name) use ($user, $instance){
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.remove.post', $name);
            $instance->assertEquals($user, $event->getUser());
        });

        $this->service->remove($user);

        $this->setExpectedException('\Aspetos\Service\Exception\UserNotFoundException');
        $this->getEntityManager()->clear();
        $user = $this->service->getUserService()->find(1);
    }


    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

}
