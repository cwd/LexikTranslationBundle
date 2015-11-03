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

use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianAddress;
use Aspetos\Model\Entity\MorticianAdministration;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Aspetos\MorticianTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MorticianTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Mortician
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        //$this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.mortician');
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
        $this->assertEquals(3, $this->service->find(3)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\MorticianNotFoundException');
        $this->service->find('foo');
    }

    public function testFindByUid()
    {
        $this->assertEquals(3, $this->service->findByUid(1001)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\MorticianNotFoundException');
        $this->service->findByUid(0002);
    }

    public function testEditMorticianFiresPreAndPostEvents()
    {
        $instance = $this;

        $mortician = $this->service->find(3);

        $name = $mortician->getName();
        $mortician->setName('something different');
        $mortician->getAddress()->setCity('othercity');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.mortician.edit.pre', function ($event, $name) use ($mortician, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\morticianEvent', $event);
                $instance->assertEquals('aspetos.event.mortician.edit.pre', $name);
                $instance->assertEquals($mortician, $event->getmortician());
            }
        );
        $dispatcher->addListener(
            'aspetos.event.mortician.edit.post', function ($event, $name) use ($mortician, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\morticianEvent', $event);
                $instance->assertEquals('aspetos.event.mortician.edit.post', $name);
                $instance->assertEquals($mortician, $event->getmortician());
            }
        );

        $this->service->flush();
        $this->assertNotEquals($name, $mortician->getName());

        $this->clearEvents(array(
            'aspetos.event.mortician.edit.pre',
            'aspetos.event.mortician.edit.post'
        ));
    }

    public function testAddSupplierById()
    {
        $mortician = $this->service->find(3);
        $supplier = $this->container->get('aspetos.service.supplier')->findByUid(1001);
        $this->service->addSupplierById($mortician, $supplier->getId());
        $this->service->flush();

        $this->assertGreaterThan(0, $mortician->getSuppliers()->count());
        $this->assertTrue($mortician->getSuppliers()->contains($supplier));
    }

    public function testAddCemeteryById()
    {
        $mortician = $this->service->find(3);
        $cemetery = $this->container->get('aspetos.service.cemetery')->find(1);
        $this->service->addCemeteryById($mortician, $cemetery->getId());
        $this->service->flush();

        $this->assertGreaterThan(0, $mortician->getCemeteries()->count());
        $this->assertTrue($mortician->getCemeteries()->contains($cemetery));
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
