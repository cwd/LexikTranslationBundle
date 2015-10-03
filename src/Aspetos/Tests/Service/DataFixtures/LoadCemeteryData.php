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

use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aspetos\Model\Entity\Country;

/**
 * Loads countries data
 */
class LoadCemeteryData extends AbstractFixture implements OrderedFixtureInterface
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

        $administration = new CemeteryAdministration();
        $administration
            ->setEmail('test@foo.bar')
            ->setFax('+43 565 656')
            ->setPhone('+43 123123')
            ->setWebpage('http://foo.bar')
            ->setRegion($region)
            ->setStreet('street3')
            ->setStreet2('street4')
            ->setZipcode('67890');

        $cemetery = new Cemetery();
        $cemetery
            ->setId(1)
            ->setName('foo')
            ->setSlug('bar2')
            ->setOwnerName('blubb')
            ->setAdministration($administration);

        $address = new CemeteryAddress();
        $address
            ->setCemetery($cemetery)
            ->setRegion($region)
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345');

        $manager->persist($cemetery);
        $metadata = $manager->getClassMetaData(get_class($cemetery));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

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
