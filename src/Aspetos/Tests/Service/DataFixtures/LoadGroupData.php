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
use Aspetos\Model\Entity\Group;

/**
 * Loads countries data
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
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

        $groups = array(
            array(
                'id' => 1,
                'role' => 'ROLE_SUPER_ADMIN',
                'name' => 'SuperAdmin'
            ),
            array(
                'id' => 2,
                'role' => 'ROLE_ADMIN',
                'name' => 'Admin'
            ),
            array(
                'id' => 3,
                'role' => 'ROLE_BACKEND_ACCESS',
                'name' => 'Backend Access'
            ),
            array(
                'id' => 4,
                'role' => 'ROLE_CUSTOMER',
                'name' => 'Customer'
            ),
            array(
                'id' => 5,
                'role' => 'ROLE_USER',
                'name' => 'User'
            ),
            array(
                'id' => 6,
                'role' => 'ROLE_SHOPMANAGER',
                'name' => 'Shopmanager'
            ),
            array(
                'id' => 7,
                'role' => 'ROLE_MORTICIAN',
                'name' => 'Mortician'
            ),
            array(
                'id' => 8,
                'role' => 'ROLE_SUPPLIER',
                'name' => 'Supplier'
            )
        );

        foreach ($groups as $group) {
            $groupObj = new Group($group['name'], array($group['role']));
            $groupObj->setId($group['id']);
            $manager->persist($groupObj);
            $metadata = $manager->getClassMetaData(get_class($groupObj));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $this->addReference($group['role'], $groupObj);
        }

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
