<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\Vtiger;

use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class Aspetos\UserTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ImporterTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Vtiger\Importer
     */
    protected $service;

    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/../DataFixtures');
        $this->loginUser('admin', $this->getUser(1));

    }

    /**
     * @skip
     */
    public function testGetClients()
    {
        try {
            $this->service = $this->container->get('aspetos.service.vtiger.importer');

            $result = $this->service->getClients(1, 10);
            $this->assertEquals(1, count($result));
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'Runs against main CRM - should be mocked - also runner cant access crm'
            );
        }
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }

}
