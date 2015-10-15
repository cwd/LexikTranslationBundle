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

use Aspetos\Model\Entity\Permission;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aspetos\Model\Entity\Region;

/**
 * Loads countries data
 */
class LoadPermissionData extends AbstractFixture implements OrderedFixtureInterface
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

        $permissions =  array(
            array('id' => '1', 'name' => 'mortician.view', 'title' => 'Mortician View'),
            array('id' => '2', 'name' => 'mortician.edit', 'title' => 'Mortician Edit'),
            array('id' => '3', 'name' => 'supplier.view', 'title' => 'Supplier View'),
        );


        foreach ($permissions as $permission) {
            $obj = new Permission();
            $obj
                ->setId($permission['id'])
                ->setName($permission['name'])
                ->setTitle($permission['title'])
                ->setEntity('mortician');

            $manager->persist($obj);

            $metadata = $manager->getClassMetaData(get_class($obj));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $this->addReference('permission-'.$permission['name'], $obj);
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
