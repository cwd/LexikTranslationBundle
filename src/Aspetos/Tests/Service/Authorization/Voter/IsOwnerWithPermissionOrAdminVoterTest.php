<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\Authorization\Voter;

use Aspetos\Model\Entity\Permission;
use Aspetos\Model\Entity\PermissionAddress;
use Aspetos\Model\Entity\PermissionAdministration;
use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class Aspetos\PermissionTest
 *
 * @package Aspetos\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class IsOwnerWithPermissionOrAdminVoterTest extends DoctrineTestCase
{
    /**
     * @var \Aspetos\Service\Permission
     */
    protected $service;

    /**
     * @var AuthorizationChecker
     */
    protected $checker;


    public function setUp()
    {
        $this->loadFixturesFromDirectory(__DIR__ . '/../../DataFixtures');
        $this->service = $this->container->get('aspetos.service.permission');
        $this->checker = $this->container->get('security.authorization_checker');

    }

    public function testVoterSupportsClass()
    {
        $this->loginUser('admin', $this->getUser(1));
        $this->assertTrue($this->getChecker()->isGranted('mortician.view', $this->container->get('aspetos.service.mortician')->find(1)));
        $this->assertFalse($this->getChecker()->isGranted('mortician.view', $this->container->get('aspetos.service.region')->find(1)));
    }

    public function testVoterIsAdmin()
    {
        $this->loginUser('admin', $this->getUser(1));
        $this->assertTrue($this->getChecker()->isGranted('mortician.view', $this->container->get('aspetos.service.mortician')->find(1)));

        $this->loginUser('admin', $this->getUser(2));
        $this->assertFalse($this->getChecker()->isGranted('mortician.view', $this->container->get('aspetos.service.mortician')->find(2)));
    }

    public function testVoterHasPermission()
    {
        $this->loginUser('admin', $this->getUser(2));
        $mortician = $this->container->get('aspetos.service.mortician')->find(1);
        $this->assertTrue($this->getChecker()->isGranted('mortician.view', $mortician));
        $this->assertFalse($this->getChecker()->isGranted('mortician.edit', $mortician));
        $this->assertFalse($this->getChecker()->isGranted('unknown.permission', $mortician));
        $this->assertFalse($this->getChecker()->isGranted('supplier.view', $mortician));
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getChecker()
    {
        return $this->checker;
    }

    protected function getUser($pid = 1)
    {
        return $this->container->get('aspetos.service.user')->find($pid);
    }
}
