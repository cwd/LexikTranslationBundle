<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\DataFixtures\ORM\Product;

use Aspetos\Model\Entity\ProductCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads suppliers data
 */
class LoadProductCategoryData extends AbstractFixture implements OrderedFixtureInterface
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

        $main = new ProductCategory();
        $main->setName('Produkte');
        $manager->persist($main);
        $manager->flush();

        $flower = new ProductCategory();
        $flower->setName('Blumen')
               ->setParent($main);
        $manager->persist($flower);
        $this->addReference('productcategory-flower', $flower);

        $category = new ProductCategory();
        $category->setName('Trauerkränze')
                 ->setParent($flower);
        $manager->persist($category);
        $this->addReference('productcategory-chaplet', $category);

        $category = new ProductCategory();
        $category->setName('Sträuße')
                 ->setParent($flower);
        $manager->persist($category);
        $this->addReference('productcategory-bouquets', $category);

        $category = new ProductCategory();
        $category->setName('Verschiedenes')
                 ->setParent($main);
        $manager->persist($category);
        $this->addReference('productcategory-misc', $category);


        $category = new ProductCategory();
        $category->setName('Kerzen')
                 ->setParent($main);
        $manager->persist($category);
        $this->addReference('productcategory-candle', $category);

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
