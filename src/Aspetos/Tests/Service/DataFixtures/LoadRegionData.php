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
use Aspetos\Model\Entity\Region;

/**
 * Loads countries data
 */
class LoadRegionData extends AbstractFixture implements OrderedFixtureInterface
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

        $provinces =  array(
            array('id' => '1','name' => 'Burgenland','country' => 'AT','short' => 'B'),
            array('id' => '2','name' => 'Kärnten','country' => 'AT','short' => 'Knt'),
            array('id' => '3','name' => 'Niederösterreich','country' => 'AT','short' => 'NÖ'),
            array('id' => '4','name' => 'Oberösterreich','country' => 'AT','short' => 'OÖ'),
            array('id' => '5','name' => 'Salzburg','country' => 'AT','short' => 'Szb'),
            array('id' => '6','name' => 'Steiermark','country' => 'AT','short' => 'Stmk'),
            array('id' => '7','name' => 'Tirol','country' => 'AT','short' => 'T'),
            array('id' => '8','name' => 'Vorarlberg','country' => 'AT','short' => 'Vbg'),
            array('id' => '9','name' => 'Wien','country' => 'AT','short' => 'W'),
            array('id' => '10','name' => 'Baden-Württemberg','country' => 'DE','short' => 'BW'),
            array('id' => '11','name' => 'Bayern','country' => 'DE','short' => 'BY'),
            array('id' => '12','name' => 'Berlin','country' => 'DE','short' => 'BE'),
            array('id' => '13','name' => 'Brandenburg','country' => 'DE','short' => 'BB'),
            array('id' => '14','name' => 'Bremen','country' => 'DE','short' => 'HB'),
            array('id' => '15','name' => 'Hamburg','country' => 'DE','short' => 'HH'),
            array('id' => '16','name' => 'Hessen','country' => 'DE','short' => 'HE'),
            array('id' => '17','name' => 'Mecklenburg-Vorpommern','country' => 'DE','short' => 'MV'),
            array('id' => '18','name' => 'Niedersachsen','country' => 'DE','short' => 'NI'),
            array('id' => '19','name' => 'Nordrhein-Westfalen','country' => 'DE','short' => 'NW'),
            array('id' => '20','name' => 'Rheinland-Pfalz','country' => 'DE','short' => 'RP'),
            array('id' => '21','name' => 'Saarland','country' => 'DE','short' => 'SL'),
            array('id' => '22','name' => 'Sachsen','country' => 'DE','short' => 'SN'),
            array('id' => '23','name' => 'Sachsen-Anhalt','country' => 'DE','short' => 'ST'),
            array('id' => '24','name' => 'Schleswig-Holstein','country' => 'DE','short' => 'SH'),
            array('id' => '25','name' => 'Thüringen','country' => 'DE','short' => 'TH')
        );


        foreach ($provinces as $province) {
            $regionObj = new Region();
            $regionObj
                ->setId($province['id'])
                ->setName($province['name'])
                ->setShort($province['short'])
                ->setCountry($province['country']);

            $manager->persist($regionObj);

            $metadata = $manager->getClassMetaData(get_class($regionObj));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            if ($regionObj->getName() == 'Wien') {
                $this->addReference('region-vienna', $regionObj);
            }
            if ($regionObj->getName() == 'Berlin') {
                $this->addReference('region-berlin', $regionObj);
            }
        }

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
