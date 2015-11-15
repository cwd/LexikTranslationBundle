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

use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Aspetos\CemeteryTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CemeteryTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Cemetery
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        //$this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.cemetery');
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

        $this->setExpectedException('\Aspetos\Service\Exception\CemeteryNotFoundException');
        $this->service->find('foo');
    }

    public function testSluggableWithUmlaut()
    {
        $cemetery = $this->service->find(2);
        $this->assertEquals('this-is-oeae-ss', $cemetery->getSlug());
    }

    public function testCreateCemeteryFiresPreAndPostEvents()
    {
        $instance = $this;

        $region = $this->getRegion();

        $administration = new CemeteryAdministration();
        $administration
            ->setEmail('test@foo.bar')
            ->setFax(PhoneNumberUtil::getInstance()->parse('+43 6464646', PhoneNumberUtil::UNKNOWN_REGION))
            ->setPhone(PhoneNumberUtil::getInstance()->parse('+43 123123', PhoneNumberUtil::UNKNOWN_REGION))
            ->setWebpage('http://foo.bar')
            ->setRegion($region)
            ->setCountry('AT')
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
            ->setCountry('AT')
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

        $this->service->persist($cemetery);
        $this->service->flush();

        $this->clearEvents(array(
            'aspetos.event.cemetery.create.pre',
            'aspetos.event.cemetery.create.post'
        ));
    }

    public function testEditCemeteryFiresPreAndPostEvents()
    {
        $instance = $this;

        $cemetery = $this->service->find(1);

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

        $this->service->flush();
        $this->assertNotEquals($name, $cemetery->getName());

        $this->clearEvents(array(
            'aspetos.event.cemetery.edit.pre',
            'aspetos.event.cemetery.edit.post'
        ));
    }

    public function testRemoveCemeteryFiresPreAndPostEvents()
    {
        $instance = $this;

        $cemetery = $this->service->find(1);

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

        $this->service->flush();

        $this->service->remove($cemetery);

        $this->clearEvents(array(
            'aspetos.event.cemetery.remove.pre',
            'aspetos.event.cemetery.remove.post'
        ));

        $this->setExpectedException('\Aspetos\Service\Exception\CemeteryNotFoundException');
        $this->getEntityManager()->clear();
        $cemetery = $this->service->find(1);
    }

    public function testFindAllActiveAsArray()
    {
        $suppliers = $this->service->findAllActiveAsArray();
        $this->assertEquals(2, count($suppliers));
    }

    protected function getRegion($pid = 1)
    {
        return $this->container->get('aspetos.service.region')->find($pid);
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

    /**
     * Removes all listeners for given events
     * @param array $events
     */
    protected function clearEvents($events = array())
    {
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

    /**
     *
     */
    public function testSearch()
    {
        $this->assertEquals(2, sizeof($this->service->search(array('address.country' => 'AT'))));
        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'DE'))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'US'))));

        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(5)))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(2)))));
        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(6)))));
        $this->assertEquals(2, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(5,6)))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'DE', 'address.district' => array(5)))));

        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT'), array(1))));

    }

}
