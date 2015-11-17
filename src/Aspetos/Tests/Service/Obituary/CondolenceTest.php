<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\Obituary;

use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\SupplierTypeTest
 *
 * @package Aspetos\Tests\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CondolenceTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\SupplierType
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/../DataFixtures');
        $this->loginUser('admin', $this->getUser(1));
        $this->service = $this->container->get('aspetos.service.obituary.condolence');
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

        $this->setExpectedException('\Aspetos\Service\Exception\CondolenceNotFoundException');
        $this->service->find('foo');
    }

    public function testFindByOrigid()
    {
        $this->assertEquals(1, $this->service->findByOrigId(1234)->getId());
        $this->setExpectedException('\Aspetos\Service\Exception\CondolenceNotFoundException');
        $this->service->findByOrigId(1004);
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }
}
