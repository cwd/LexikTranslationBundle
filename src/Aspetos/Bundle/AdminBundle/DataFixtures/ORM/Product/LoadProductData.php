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

use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Entity\ProductHasCategory;
use Cwd\MediaBundle\Service\MediaService;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loads suppliers data
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $manager->clear();
        gc_collect_cycles(); // Could be useful if you have a lot of fixtures


        $products = array(
            array(
                'name' => 'Immer und Ewig - Errinerungskristalle',
                'description' => "Erinnerungskristalle werden aus Glas und der Asche des Verstorbenen gefertigt. Sie erleichtern das privater Erinnern und das Gedenken daheim.\n\nDiese Glasarbeiten werden zur Gänze durch renommierte Glaskünstler in der Schweiz hergestellt.",
                'sellPrice'   => 59.90,
                'basePrice'   => 35,
                'image'       => 'Data/kristall.jpg',
                'category'    => array(
                    $this->getReference('productcategory-misc')
                ),
                'images'      => array(
                    'Data/kristall.jpg'
                )
            ),
            array(
                'name' => 'Erinnerungsrose',
                'description'   => "Die Rose ist ein uraltes Symbol.\n\nSie ist Schmuck und Erinnerungsstück, hat eine tiefe zeremonielle Bedeutung und erhält das Gefühl der Verbundenheit.\n\nEdel und handgemacht.",
                'sellPrice'     => 29.50,
                'basePrice'     => 13,
                'image'         => 'Data/rose.jpg',
                'category'      => array(
                    $this->getReference('productcategory-flower')
                ),
                'images'        => array(
                    'Data/rose.jpg'
                )
            ),
            array(
                'name' => 'Klamottchen',
                'description'   => "Aus den Kleidern des Verstorbenen werden diese ganz besonderen Übergangsobjekte zum festhalten gefertigt. Sie helfen Kindern und Erwachsenen in sehr intensiven Trauerzeiten.\n\nKirsten Strohmeier näht jedes Klamottchen mit viel Liebe zum Detail.",
                'sellPrice'     => 44.90,
                'basePrice'     => 30,
                'image'         => 'Data/klamottchen.jpg',
                'category'      => array(
                    $this->getReference('productcategory-misc')
                ),
                'images'        => array(
                    'Data/klamottchen.jpg'
                )
            ),
            array(
                'name' => 'Friedhofsbote',
                'description'   => "Lorem Ipsum",
                'sellPrice'     => 29.90,
                'basePrice'     => 18,
                'image'         => 'Data/blumen_a.jpg',
                'category'      => array(
                    $this->getReference('productcategory-flower')
                ),
                'images'        => array(
                    'Data/blumen_a.jpg',
                )
            ),
            array(
                'name' => 'Friedhofsbote Teller',
                'description'   => "Lorem Ipsum",
                'sellPrice'     => 34.90,
                'basePrice'     => 23,
                'image'         => 'Data/blumen_b.jpg',
                'category'      => array(
                    $this->getReference('productcategory-bouquets')
                ),
                'images'        => array(
                    'Data/blumen_b.jpg'
                )
            )
        );


        foreach ($products as $product) {
            $this->addProduct($product);
        }
        $manager->flush();
    }

    /**
     * @param array $data
     */
    protected function addProduct($data)
    {
        $product = new Product();
        $product->setName($data['name'])
                ->setDescription($data['description'])
                ->setState(true)
                ->setBasePrice($data['basePrice'])
                ->setSellPrice($data['sellPrice']);

        $this->manager->persist($product);

        foreach ($data['category'] as $productCategory) {
            $category = new ProductHasCategory();
            $category->setProduct($product)
                     ->setProductCategory($productCategory);
            $this->manager->persist($category);
        }

        return $this->addImage($product, $data);
    }

    /**
     * @param Product $product
     * @param array   $data
     */
    protected function addImage(Product $product, $data)
    {
        /** @var MediaService $mediaService */
        $mediaService = $this->container->get('cwd.media.service');

        if (file_exists(__DIR__.'/'.$data['image'])) {
            $image = $mediaService->create(__DIR__ . '/' . $data['image'], true);
            $this->manager->flush($image);
            $product->setMainImage($image);
        }

        foreach ($data['images'] as $image) {
            if (file_exists(__DIR__.'/'.$image)) {
                $image = $mediaService->create(__DIR__ . '/' . $image, true);
                $this->manager->flush($image);
                $product->addMedia($image);
            }
        }

        return $product;
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
