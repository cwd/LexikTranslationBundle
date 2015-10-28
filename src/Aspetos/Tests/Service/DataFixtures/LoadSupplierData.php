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

use Aspetos\Model\Entity\Supplier;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads suppliers data
 */
class LoadSupplierData extends AbstractFixture implements OrderedFixtureInterface
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

        $supplier = new Supplier();
        $supplier->setPartnerVienna(1)
                  ->setCountry('AT')
                  ->setEmail('foo@bar.at')
                  ->setWebpage('http://www.foobar.at')
                  ->setName('Demo Lieferant')
                  ->setState(1)
                  ->setOrigId(1001);

        $manager->persist($supplier);

        $this->addReference('supplier', $supplier);

        $supplier = new Supplier();
        $supplier->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setName('Demo Lieferant 2')
            ->setState(1)
            ->setOrigId(1002);

        $manager->persist($supplier);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
