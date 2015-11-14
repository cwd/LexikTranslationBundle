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
use Aspetos\Model\Entity\SupplierType;

/**
 * Loads countries data
 */
class LoadSupplierTypeData extends AbstractFixture implements OrderedFixtureInterface
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

        $supplierType = new SupplierType();
        $supplierType->setName('Test Typ 1');
        $supplierType->setOrigId(1001);
        $manager->persist($supplierType);
        $this->addReference('supplier-type-1', $supplierType);

        $supplierType = new SupplierType();
        $supplierType->setName('Test Typ 2');
        $supplierType->setOrigId(1002);
        $manager->persist($supplierType);
        $this->addReference('supplier-type-2', $supplierType);

        $supplierType = new SupplierType();
        $supplierType->setName('Test Typ 3');
        $supplierType->setOrigId(1003);
        $manager->persist($supplierType);
        $this->addReference('supplier-type-3', $supplierType);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
