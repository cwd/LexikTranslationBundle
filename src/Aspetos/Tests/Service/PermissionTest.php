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

use Aspetos\Model\Entity\Permission;
use Aspetos\Model\Entity\PermissionAddress;
use Aspetos\Model\Entity\PermissionAdministration;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Aspetos\PermissionTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class PermissionTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Permission
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        //$this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.permission');
    }

    public function testEntityManager()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->service->getEm());
    }

    public function testGetNewEntity()
    {
        $this->assertNull($this->service->getNew()->getId());
    }

    public function testFindEntity()
    {
        $this->assertEquals(1, $this->service->find(1)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\PermissionNotFoundException');
        $this->service->find('foo');
    }

    public function testCreatePermissionFiresPreAndPostEvents()
    {
        $instance = $this;

        $permission = new Permission();
        $permission->setName('mortician.dummy')
                   ->setTitle('dummy')
                   ->setEntity('dummy') ;

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.permission.create.pre', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.create.pre', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );

        $dispatcher->addListener(
            'aspetos.event.permission.create.post', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.create.post', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );

        $this->service->persist($permission);
        $this->service->flush();

        $this->clearEvents(array(
            'aspetos.event.permission.create.pre',
            'aspetos.event.permission.create.post'
        ));
    }

    public function testEditPermissionFiresPreAndPostEvents()
    {
        $instance = $this;

        $permission = $this->service->find(1);

        $name = $permission->getTitle();
        $permission->setTitle('something different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.permission.edit.pre', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.edit.pre', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );
        $dispatcher->addListener(
            'aspetos.event.permission.edit.post', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.edit.post', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );

        $this->service->flush();
        $this->assertNotEquals($name, $permission->getTitle());

        $this->clearEvents(array(
            'aspetos.event.permission.edit.pre',
            'aspetos.event.permission.edit.post'
        ));
    }

    public function testRemovePermissionFiresPreAndPostEvents()
    {
        $instance = $this;

        $permission = $this->service->find(1);

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.permission.remove.pre', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.remove.pre', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );
        $dispatcher->addListener(
            'aspetos.event.permission.remove.post', function ($event, $name) use ($permission, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\PermissionEvent', $event);
            $instance->assertEquals('aspetos.event.permission.remove.post', $name);
            $instance->assertEquals($permission, $event->getPermission());
        }
        );

        $this->service->flush();

        $this->service->remove($permission);

        $this->clearEvents(array(
            'aspetos.event.permission.remove.pre',
            'aspetos.event.permission.remove.post'
        ));

        $this->setExpectedException('\Aspetos\Service\Exception\PermissionNotFoundException');
        $this->getEntityManager()->clear();
        $permission = $this->service->find(1);
    }


    public function testFindAllAsArray()
    {
        $data = $this->service->findAllAsArray();
        $this->assertTrue(is_array($data));
        $this->assertTrue(in_array('mortician.view', $data));
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
