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
use Aspetos\Model\Entity\Country;

/**
 * Loads countries data
 */
class LoadCountryData extends AbstractFixture implements OrderedFixtureInterface
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


        $countryObj = new Country();
        $countryObj->setName("Austria")
                   ->setAlpha2("AT")
                   ->setAlpha3("AUT")
                   ->setCountryCode('040');

        $manager->persist($countryObj);

        $this->addReference('country-austria', $countryObj);

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
