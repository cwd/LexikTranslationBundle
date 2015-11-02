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

        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
                  ->setCountry('AT')
                  ->setEmail('foo@bar.at')
                  ->setWebpage('http://www.foobar.at')
                  ->setContactName('Mortician NAme')
                  ->setName('Demo Bestatter')
                  ->setState('active')
                  ->setOrigId(1001);

        $manager->persist($mortician);

        $address = new MorticianAddress();
        $address->setCity('Musterstadt')
            ->setZipcode(1234)
            ->setCountry('ZZ')
            ->setMortician($mortician)
            ->setStreet('Musterstrasse')
            ->setDistrict($this->getReference('district-1'));

        $manager->persist($address);

        $mortician->setAddress($address);

        $this->addReference('mortician', $mortician);
        $manager->flush();


        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setContactName('Other Mortician NAme')
            ->setName('Demo Bestatter 2')
            ->setState('active')
            ->setOrigId(1002);

        $manager->persist($mortician);

        $manager->flush();

        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('foo3@bar.at')
            ->setWebpage('http://www.foobar3.at')
            ->setContactName('Other Mortician NAme 2')
            ->setName('Demo Bestatter 3')
            ->setState('active')
            ->setOrigId(1003);

        $manager->persist($mortician);

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
