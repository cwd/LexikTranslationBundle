<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service;

use Aspetos\Model\Entity\Admin;
use Aspetos\Service\Event\UserEvent;
use Aspetos\Service\Listener\UserPasswordListener;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\UserTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class UserTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\User
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        $this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.user');
    }

    public function testEntityManager()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->service->getEm());
    }

    public function testGetNewEntity()
    {
        $this->assertNull($this->service->getNew()->getId());
    }

    public function testFind()
    {
        $this->assertEquals(1, $this->service->find(1)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\UserNotFoundException');
        $this->service->find('foo');
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

        $dispatcher->addListener('aspetos.event.user.create.pre', function ($event, $name) use ($user, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.create.pre', $name);
            $instance->assertEquals($user->getEmail(), $event->getUser()->getEmail());

            $listener = new UserPasswordListener();
            $listener->setPassword(new UserEvent($user));
        });
        $dispatcher->addListener('aspetos.event.user.create.post', function ($event, $name) use ($user, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.create.post', $name);
            $instance->assertEquals($user->getEmail(), $event->getUser()->getEmail());
        });

        $this->service->persist($user);
        $this->service->flush();

        $this->clearEvents(array(
            'aspetos.event.user.create.pre',
            'aspetos.event.user.create.post'
        ));

        $this->assertNotEquals(null, $user->getSalt());
        $this->assertNotEquals(null, $user->getPassword());
    }

    public function testEditUser()
    {
        $instance = $this;

        $user = $this->service->find(1);

        $lastname = $user->getLastname();
        $user->setLastname('somthing different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('aspetos.event.user.edit.pre', function ($event, $name) use ($user, $instance, $lastname) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.edit.pre', $name);
            $instance->assertNotEquals($lastname, $event->getUser()->getLastname());
        });
        $dispatcher->addListener('aspetos.event.user.edit.post', function ($event, $name) use ($user, $instance, $lastname) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.edit.post', $name);
            $instance->assertNotEquals($lastname, $event->getUser()->getLastname());
            $instance->assertEquals($user->getLastname(), $event->getUser()->getLastname());
        });

        #dump($dispatcher->getListeners('aspetos.event.user.edit.pre'));

        $this->service->flush();

        $this->clearEvents(array(
            'aspetos.event.user.edit.pre',
            'aspetos.event.user.edit.post'
        ));
        $this->assertNotEquals($lastname, $user->getLastname());
    }

    public function testremoveUser()
    {
        $instance = $this;

        $user = $this->service->find(1);

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('aspetos.event.user.remove.pre', function ($event, $name) use ($user, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.remove.pre', $name);
            $instance->assertEquals($user->getId(), $event->getUser()->getId());
        });
        $dispatcher->addListener('aspetos.event.user.remove.post', function ($event, $name) use ($user, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\UserEvent', $event);
            $instance->assertEquals('aspetos.event.user.remove.post', $name);
            $instance->assertEquals($user->getId(), $event->getUser()->getId());
        });

        $this->service->remove($user);

        $this->clearEvents(array(
            'aspetos.event.user.remove.pre',
            'aspetos.event.user.remove.post'
        ));

        $this->setExpectedException('\Aspetos\Service\Exception\UserNotFoundException');
        $this->getEntityManager()->clear();
        $user = $this->service->find(1);
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

    /**
     * Removes all listeners for given events
     * @param array $events
     */
    protected function clearEvents($events = array()) {
        $dispatcher = $this->container->get('event_dispatcher');
        foreach ($events as $event) {
            $listeners = $dispatcher->getListeners($event);
            foreach ($listeners as $listener) {
                if ($listener instanceof \Closure) {
                    $dispatcher->removeListener($event, $listener);
                }
            }
        }
    }

}
