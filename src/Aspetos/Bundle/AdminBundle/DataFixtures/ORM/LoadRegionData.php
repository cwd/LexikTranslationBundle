<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\DataFixtures\ORM;

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
            array('name' => 'Burgenland','nameShort' => 'B','country' => 'AT'),
            array('name' => 'Kärnten','nameShort' => 'Knt','country' => 'AT'),
            array('name' => 'Niederösterreich','nameShort' => 'NÖ','country' => 'AT'),
            array('name' => 'Oberösterreich','nameShort' => 'OÖ','country' => 'AT'),
            array('name' => 'Salzburg','nameShort' => 'Szb','country' => 'AT'),
            array('name' => 'Steiermark','nameShort' => 'Stmk','country' => 'AT'),
            array('name' => 'Tirol','nameShort' => 'T','country' => 'AT'),
            array('name' => 'Vorarlberg','nameShort' => 'Vbg','country' => 'AT'),
            array('name' => 'Wien','nameShort' => 'W','country' => 'AT'),
            array('name' => 'Baden-Württemberg','nameShort' => 'BW','country' => 'DE'),
            array('name' => 'Bayern','nameShort' => 'BY','country' => 'DE'),
            array('name' => 'Berlin','nameShort' => 'BE','country' => 'DE'),
            array('name' => 'Brandenburg','nameShort' => 'BB','country' => 'DE'),
            array('name' => 'Bremen','nameShort' => 'HB','country' => 'DE'),
            array('name' => 'Hamburg','nameShort' => 'HH','country' => 'DE'),
            array('name' => 'Hessen','nameShort' => 'HE','country' => 'DE'),
            array('name' => 'Mecklenburg-Vorpommern','nameShort' => 'MV','country' => 'DE'),
            array('name' => 'Niedersachsen','nameShort' => 'NI','country' => 'DE'),
            array('name' => 'Nordrhein-Westfalen','nameShort' => 'NW','country' => 'DE'),
            array('name' => 'Rheinland-Pfalz','nameShort' => 'RP','country' => 'DE'),
            array('name' => 'Saarland','nameShort' => 'SL','country' => 'DE'),
            array('name' => 'Sachsen','nameShort' => 'SN','country' => 'DE'),
            array('name' => 'Sachsen-Anhalt','nameShort' => 'ST','country' => 'DE'),
            array('name' => 'Schleswig-Holstein','nameShort' => 'SH','country' => 'DE'),
            array('name' => 'Thüringen','nameShort' => 'TH','country' => 'DE')
        );


        foreach ($provinces as $province) {
            $regionObj = new Region();
            $regionObj
                ->setName($province['name'])
                ->setShort($province['nameShort'])
                ->setCountry($province['country']);

            $manager->persist($regionObj);

            if ($regionObj->getName() == 'Wien') {
                $this->addReference('region-vienna', $regionObj);
            }
        }

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
