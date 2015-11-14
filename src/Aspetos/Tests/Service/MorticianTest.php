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
        $this->service = $this->container->get('aspetos.service.Mortician');
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

        $this->setExpectedException('\Aspetos\Service\Exception\MorticianNotFoundException');
        $this->service->find('foo');
    }

    public function testFindByUid()
    {
        $this->assertEquals(1, $this->service->findByUid(1001)->getId());

        $this->setExpectedException('\Aspetos\Service\Exception\MorticianNotFoundException');
        $this->service->findByUid(0002);
    }

    public function testEditMorticianFiresPreAndPostEvents()
    {
        $instance = $this;

        $mortician = $this->service->find(1);

        $name = $mortician->getName();
        $mortician->setName('something different');

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

    /**
     *
     */
    public function testSearch()
    {
        $this->assertEquals(2, sizeof($this->service->search(array('address.country' => 'AT'))));
        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'DE'))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'US'))));

        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(1)))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(2)))));
        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(3)))));
        $this->assertEquals(2, sizeof($this->service->search(array('address.country' => 'AT', 'address.district' => array(1,3)))));
        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'DE', 'address.district' => array(1)))));
        $this->assertEquals(0, sizeof($this->service->search(array('address.country' => 'DE', 'address.district' => array(3)))));

        $this->assertEquals(1, sizeof($this->service->search(array('address.country' => 'AT'), array(1))));
    }
}
