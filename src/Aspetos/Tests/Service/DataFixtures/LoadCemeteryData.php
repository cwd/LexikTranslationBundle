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
use libphonenumber\PhoneNumberUtil;

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

        $regionVienna = $this->getReference('region-vienna');
        $regionBerlin = $this->getReference('region-vienna');

        $district1 = $this->getReference('district-imst');
        $district2 = $this->getReference('district-landeck');
        $district3 = $this->getReference('district-berlin');

        $administration = new CemeteryAdministration();
        $administration
            ->setEmail('test@foo.bar')
            ->setFax(PhoneNumberUtil::getInstance()->parse('+43 6464646', PhoneNumberUtil::UNKNOWN_REGION))
            ->setPhone(PhoneNumberUtil::getInstance()->parse('+43 6464646', PhoneNumberUtil::UNKNOWN_REGION))
            ->setWebpage('http://foo.bar')
            ->setRegion($regionVienna)
            ->setDistrict($district1)
            ->setCountry('AT')
            ->setStreet('street3')
            ->setStreet2('street4')
            ->setZipcode('67890');

        $address = new CemeteryAddress();
        $address
            ->setRegion($regionVienna)
            ->setCountry('AT')
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345')
            ->setDistrict($district1);

        $cemetery = new Cemetery();
        $cemetery
            ->setId(1)
            ->setName('foo')
            ->setOwnerName('blubb')
            ->setAdministration($administration)
            ->setAddress($address);

        $manager->persist($cemetery);

        $address2 = new CemeteryAddress();
        $address2
            ->setRegion($regionVienna)
            ->setCountry('AT')
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setZipcode('12345')
            ->setDistrict($district2);

        $cemetery2 = new Cemetery();
        $cemetery2
            ->setId(2)
            ->setName("this is öä ß!")
            ->setOwnerName('foobar')
            ->setAdministration($administration)
            ->setAddress($address2);
        $manager->persist($cemetery2);

        $address3 = new CemeteryAddress();
        $address3
            ->setRegion($regionBerlin)
            ->setCountry('DE')
            ->setStreet('street1')
            ->setStreet2('street2')
            ->setDistrict($district3)
            ->setZipcode('12345');

        $cemetery3 = new Cemetery();
        $cemetery3
            ->setId(3)
            ->setName("test3!")
            ->setOwnerName('test')
            ->setAdministration($administration)
            ->setAddress($address3);
        $manager->persist($cemetery3);

        $metadata = $manager->getClassMetaData(get_class($cemetery));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $manager->flush();
        $this->setReference('cemetery', $cemetery);

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}
