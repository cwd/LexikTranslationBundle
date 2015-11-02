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

use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Entity\SupplierAddress;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Aspetos\SupplierTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class SupplierTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Supplier\Supplier
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        //$this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.supplier.supplier');
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

        $this->setExpectedException('\Aspetos\Service\Exception\SupplierNotFoundException');
        $this->service->find('foo');
    }

    public function testFindByUid()
    {
        $this->assertEquals(1, $this->service->findByUid(1001)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\SupplierNotFoundException');
        $this->service->findByUid(0002);
    }

    public function testEditSupplierFiresPreAndPostEvents()
    {
        $instance = $this;

        $supplier = $this->service->find(1);

        $name = $supplier->getName();
        $supplier->setName('something different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.supplier.edit.pre', function ($event, $name) use ($supplier, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\supplierEvent', $event);
            $instance->assertEquals('aspetos.event.supplier.edit.pre', $name);
            $instance->assertEquals($supplier, $event->getSupplier());
        }
        );
        $dispatcher->addListener(
            'aspetos.event.supplier.edit.post', function ($event, $name) use ($supplier, $instance) {
            $instance->assertInstanceOf('Aspetos\Service\Event\supplierEvent', $event);
            $instance->assertEquals('aspetos.event.supplier.edit.post', $name);
            $instance->assertEquals($supplier, $event->getSupplier());
        }
        );

        $this->service->flush();
        $this->assertNotEquals($name, $supplier->getName());

        $this->clearEvents(array(
            'aspetos.event.supplier.edit.pre',
            'aspetos.event.supplier.edit.post'
        ));
    }

    public function testFindAllActiveAsArray()
    {
        $suppliers = $this->service->findAllActiveAsArray();
        $this->assertEquals(2, count($suppliers));
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
