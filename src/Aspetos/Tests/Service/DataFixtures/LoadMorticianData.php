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

use Aspetos\Model\Entity\Mortician;
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
                  ->setState(1)
                  ->setOrigMorticianId(1001);

        $manager->persist($mortician);

        $this->addReference('mortician', $mortician);

        $mortician = new Mortician();
        $mortician->setPartnerVienna(1)
            ->setCountry('AT')
            ->setEmail('fo2o@bar.at')
            ->setWebpage('http://www.fooba2r.at')
            ->setContactName('Other Mortician NAme')
            ->setName('Demo Bestatter 2')
            ->setState(1)
            ->setOrigMorticianId(1002);

        $manager->persist($mortician);

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
