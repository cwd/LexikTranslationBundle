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

use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Entity\ProductHasCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads suppliers data
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
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

        $product = new Product();
        $product->setSupplier($this->getReference('supplier'))
                ->setName('Trauerkristall')
                ->setSellPrice(59.90)
                ->setBasePrice(35)
                ->setDescription("Erinnerungskristalle werden aus Glas und der Asche des Verstorbenen gefertigt. Sie erleichtern das privater Erinnern und das Gedenken daheim.\n\nDiese Glasarbeiten werden zur Gänze durch renommierte Glaskünstler in der Schweiz hergestellt.");



        $manager->persist($flower);

        $category = new ProductHasCategory();

        $this->addReference('productcategory-flower', $category);


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
