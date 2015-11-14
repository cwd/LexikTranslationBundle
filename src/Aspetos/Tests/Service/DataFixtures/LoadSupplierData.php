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
use Aspetos\Model\Entity\SupplierAddress;
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

        $region = $this->getReference('region-vienna');
        $district1 = $this->getReference('district-biberach');
        $district2 = $this->getReference('district-ravensburg');
        $supplierType1 = $this->getReference('supplier-type-1');
        $supplierType2 = $this->getReference('supplier-type-2');
        $supplierType3 = $this->getReference('supplier-type-3');

        $address = new SupplierAddress();
        $address
            ->setRegion($region)
            ->setCountry('AT')
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345')
            ->setDistrict($district1);

        $supplier = new Supplier();
        $supplier
            ->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('foo@bar.at')
            ->setWebpage('http://www.foobar.at')
            ->setName('Demo Lieferant')
            ->setState(1)
            ->setOrigId(1001)
            ->addSupplierType($supplierType1)
            ->setAddress($address);

        $manager->persist($supplier);

        $this->addReference('supplier', $supplier);

        $address = new SupplierAddress();
        $address
            ->setRegion($region)
            ->setCountry('AT')
            ->setStreet('street2')
            ->setStreet2('street3')
            ->setZipcode('11111')
            ->setDistrict($district2);

        $supplier = new Supplier();
        $supplier->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setName('Demo Lieferant 2')
            ->setState(1)
            ->setOrigId(1002)
            ->addSupplierType($supplierType1)
            ->addSupplierType($supplierType2)
            ->setAddress($address);

        $manager->persist($supplier);

        $address = new SupplierAddress();
        $address
            ->setRegion($region)
            ->setCountry('DE')
            ->setStreet('street3')
            ->setStreet2('street4')
            ->setZipcode('11112')
            ->setDistrict($district2);

        $supplier = new Supplier();
        $supplier
            ->setPartnerVienna(0)
            ->setCountry('DE')
            ->setEmail('fo2o@bar.de')
            ->setWebpage('http://www.fooba2r.at')
            ->setName('Demo Lieferant 3')
            ->setState(1)
            ->setOrigId(1002)
            ->addSupplierType($supplierType3)
            ->setAddress($address);

        $manager->persist($supplier);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
}
