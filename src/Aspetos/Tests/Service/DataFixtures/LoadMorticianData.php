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

use Aspetos\Model\Entity\Address;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianAddress;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads countries data
 */
class LoadMorticianData extends AbstractFixture implements OrderedFixtureInterface
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

        $regionVienna = $this->getReference('region-vienna');
        $regionBerlin = $this->getReference('region-berlin');
        $district1 = $this->getReference('district-imst');
        $district2 = $this->getReference('district-landeck');
        $district3 = $this->getReference('district-berlin');

        $address = new MorticianAddress();
        $address
            ->setRegion($regionVienna)
            ->setCountry('AT')
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345')
            ->setDistrict($district1);

        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
                  ->setCountry('AT')
                  ->setEmail('foo@bar.at')
                  ->setWebpage('http://www.foobar.at')
                  ->setContactName('Mortician NAme')
                  ->setName('Demo Bestatter')
                  ->setOrigId(1001)
                  ->setState('active')
                  ->setAddress($address);

        $manager->persist($mortician);

        $this->addReference('mortician', $mortician);

        $address = new MorticianAddress();
        $address
            ->setRegion($regionVienna)
            ->setCountry('AT')
            ->setStreet('street2')
            ->setStreet2('street3')
            ->setZipcode('11111')
            ->setDistrict($district2);

        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setContactName('Other Mortician NAme')
            ->setName('Demo Bestatter 2')
            ->setState('active')
            ->setOrigId(1002)
            ->setAddress($address);

        $manager->persist($mortician);

        $address = new MorticianAddress();
        $address
            ->setRegion($regionVienna)
            ->setCountry('DE')
            ->setStreet('street2')
            ->setStreet2('street3')
            ->setZipcode('11111')
            ->setDistrict($district3);
        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
            ->setCountry('DE')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setContactName('Other Mortician NAme')
            ->setName('Demo Bestatter 2')
            ->setOrigId(1003)
            ->setAddress($address)
            ->setState('active');

        $manager->persist($mortician);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}
