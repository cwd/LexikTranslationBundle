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

use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\CemeteryAdministrationTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CemeteryAdministrationTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\CemeteryAdministrationService
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        //$this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.cemetery.administration');
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

        $this->setExpectedException('\Aspetos\Service\Exception\CemeteryAdministrationNotFoundException');
        $this->service->find('foo');
    }

    public function testFindOneByNameAndAddress()
    {
        $this->assertEquals(1, $this->service->findOneByNameAndAddress('testname', 'teststreet', 'Vienna', '1160')->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\CemeteryAdministrationNotFoundException');
        $this->service->findOneByNameAndAddress('something different', 'teststreet', 'Vienna', '1160');
    }

    public function testEditCemeteryAdministrationFiresPreAndPostEvents()
    {
        $instance = $this;

        $cemeteryAdministration = $this->service->find(1);

        $name = $cemeteryAdministration->getName();
        $cemeteryAdministration->setName('something different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.cemeteryAdministration.edit.pre', function ($event, $name) use ($cemeteryAdministration, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\cemeteryAdministrationEvent', $event);
            $instance->assertEquals('aspetos.event.cemeteryAdministration.edit.pre', $name);
            $instance->assertEquals($cemeteryAdministration, $event->getCemeteryAdministration());
        }
        );
        $dispatcher->addListener(
            'aspetos.event.cemeteryAdministration.edit.post', function ($event, $name) use ($cemeteryAdministration, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\cemeteryAdministrationEvent', $event);
            $instance->assertEquals('aspetos.event.cemeteryAdministration.edit.post', $name);
            $instance->assertEquals($cemeteryAdministration, $event->getCemeteryAdministration());
        }
        );

        $this->service->flush();
        $this->assertNotEquals($name, $cemeteryAdministration->getName());

        $this->clearEvents(array(
            'aspetos.event.cemeteryAdministration.edit.pre',
            'aspetos.event.cemeteryAdministration.edit.post'
        ));
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
