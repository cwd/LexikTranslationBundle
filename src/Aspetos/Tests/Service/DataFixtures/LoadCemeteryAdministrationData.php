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

use Aspetos\Model\Entity\CemeteryAdministration;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use libphonenumber\PhoneNumberUtil;

/**
 * Loads countries data
 */
class LoadCemeteryAdministrationData extends AbstractFixture implements OrderedFixtureInterface
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
        //    ->setId(1)
            ->setName('testname')
            ->setEmail('test@foo.bar')
            ->setFax(PhoneNumberUtil::getInstance()->parse('+43 6464646', PhoneNumberUtil::UNKNOWN_REGION))
            ->setPhone(PhoneNumberUtil::getInstance()->parse('+43 6464646', PhoneNumberUtil::UNKNOWN_REGION))
            ->setWebpage('http://foo.bar')
            ->setRegion($region)
            ->setCountry('AT')
            ->setStreet('teststreet')
            ->setStreet2('street4')
            ->setCity('Vienna')
            ->setZipcode('1160');

        $manager->persist($administration);
        //$metadata = $manager->getClassMetaData(get_class($administration));
        //$metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $manager->flush();

        $this->addReference('cemetery-administration', $administration);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
