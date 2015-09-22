<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aspetos\Model\Entity\Role;

/**
 * Loads countries data
 */
class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        gc_collect_cycles(); // Could be useful if you have a lot of fixtures


        $role = new Role();
        $role->setRole('ROLE_GUEST')
            ->setName('guest')
            ->setId(1);
        $manager->persist($role);


        $roleUser = new Role();
        $roleUser->setRole('ROLE_USER')
            ->setName('user')
            ->setId(2);
        $manager->persist($roleUser);

        $roleAdmin = new Role();
        $roleAdmin->setRole('ROLE_ADMIN')
            ->setName('admin')
            ->setId(3);
        $manager->persist($roleAdmin);

        $this->addReference('role-admin', $roleAdmin);

        $role = new Role();
        $role->setRole('ROLE_SUPER_ADMIN')
            ->setName('superadmin')
            ->setId(4);
        $manager->persist($role);

        $this->addReference('role-super', $role);


        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
