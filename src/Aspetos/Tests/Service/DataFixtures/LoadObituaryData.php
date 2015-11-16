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

use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Supplier;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads suppliers data
 */
class LoadObituaryData extends AbstractFixture implements OrderedFixtureInterface
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

        $obituary = new Obituary();
        $obituary->setFirstname('Max')
                 ->setLastname('Mustermann')
                 ->setDayOfBirth(new \DateTime())
                 ->setDayOfDeath(new \Datetime())
                 ->setCemetery($this->getReference('cemetery'))
                 ->setGender('m')
                 ->setCountry('AT')
                 ->setDistrict($this->getReference('district-1'));

        $manager->persist($obituary);

        $this->addReference('obituary', $obituary);

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
