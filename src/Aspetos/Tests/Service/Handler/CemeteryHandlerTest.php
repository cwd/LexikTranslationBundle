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

use Aspetos\Model\Entity\Address;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Aspetos\Model\Entity\Country;
use Aspetos\Service\Event\CemeteryEvent;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\Handler\CemeteryHandlerTest
 *
 * @package Aspetos\Tests\Service\Handler
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CemeteryHandlerTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Handler\CemeteryHandler
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/../DataFixtures');
        $this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.handler.cemetery');
    }

    public function testEntityManager()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->service->getEm());
    }

    public function testHasCemeteryService()
    {
        $this->assertInstanceOf('Aspetos\Service\CemeteryService', $this->service->getCemeteryService());
    }

    public function testCreateCemetery()
    {
        $instance = $this;

        $region = $this->getRegion();

        $administration = new CemeteryAdministration();
        $administration
            ->setEmail('test@foo.bar')
            ->setFax('+43 565 656')
            ->setPhone('+43 123123')
            ->setWebpage('http://foo.bar')
            ->setRegion($region)
            ->setStreet('street3')
            ->setStreet2('street4')
            ->setZipcode('67890');

        $cemetery = new Cemetery();
        $cemetery->setName('foo')
            ->setSlug('bar')
            ->setOwnerName('blubb')
            ->setAdministration($administration);

        $address = new CemeteryAddress();
        $address
            ->setCemetery($cemetery)
            ->setRegion($region)
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.cemetery.create.pre', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.create.pre', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );


        $dispatcher->addListener(
            'aspetos.event.cemetery.create.post', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.create.post', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );

        $this->service->create($cemetery);
    }

    public function testEditCemetery()
    {
        $instance = $this;

        $cemetery = $this->service->getCemeteryService()->find(1);

        $name = $cemetery->getName();
        $cemetery->setName('something different');

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.cemetery.edit.pre', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.edit.pre', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );
        $dispatcher->addListener(
            'aspetos.event.cemetery.edit.post', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.edit.post', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );

        $this->service->edit($cemetery);
        $this->assertNotEquals($name, $cemetery->getName());
    }

    public function testremoveCemetery()
    {
        $instance = $this;

        $cemetery = $this->service->getCemeteryService()->find(1);

        // Test Events
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener(
            'aspetos.event.cemetery.remove.pre', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.remove.pre', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );
        $dispatcher->addListener(
            'aspetos.event.cemetery.remove.post', function ($event, $name) use ($cemetery, $instance) {
                $instance->assertInstanceOf('Aspetos\Service\Event\CemeteryEvent', $event);
                $instance->assertEquals('aspetos.event.cemetery.remove.post', $name);
                $instance->assertEquals($cemetery, $event->getCemetery());
            }
        );

        $this->service->remove($cemetery);

        $this->setExpectedException('\Aspetos\Service\Exception\CemeteryNotFoundException');
        $this->getEntityManager()->clear();
        $cemetery = $this->service->getCemeteryService()->find(1);
    }

    protected function getRegion($pid = 1)
    {
        return $this->container->get('aspetos.service.region')->find($pid);
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

}
