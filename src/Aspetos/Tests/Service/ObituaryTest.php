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
 * Class Aspetos\ObituaryTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ObituaryTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Obituary
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/DataFixtures');
        $this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.obituary');
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

        $this->setExpectedException('\Aspetos\Service\Exception\ObituaryNotFoundException');
        $this->service->find('foo');
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

}
