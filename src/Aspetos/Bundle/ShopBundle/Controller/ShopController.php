<?php

namespace Aspetos\Bundle\ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Aspetos\Service\ProductService;
use Aspetos\Service\Product\CategoryService;
use Aspetos\Service\Shop\Shop;

/**
 * Class ShopController
 *
 * @package Aspetos\Bundle\ShopBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/shop")
 */
class ShopController extends Controller
{
    /**
     * Shop index page with "popular products" overview.
     *
     * @Route("", name="aspetos_shop_index")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $popular = array();

        return $this->render('AspetosShopBundle:Shop:index.html.twig', array(
            'popularProducts' => $popular,
        ));
    }

    /**
     * Shopping cart page
     *
     * @Route("/cart", name="aspetos_shop_cart")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction(Request $request)
    {
        $shop = $this->getShop();
        $order = $shop->getOrCreateOrder();

        $form = $this->createForm('aspetos_shop_customer_order', $order);

        return $this->render('AspetosShopBundle:Shop:cart.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Product detail page.
     *
     * @Route("/p/{slug}", name="aspetos_shop_product")
     *
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productAction($slug)
    {
        $product = $this->getProductService()->findOneBySlug($slug);

        // dummy code to add product to cart upon each visit
        $shop = $this->getShop();
        $order = $shop->getOrCreateOrder();
        $order->addProduct($product);
        $shop->updateOrder($order);
        dump($order);

        return $this->render('AspetosShopBundle:Shop:product.html.twig', array(
            'product' => $product,
        ));
    }

    /**
     * Shop category page.
     *
     * @Route("/{slug}", name="aspetos_shop_category", requirements={"slug" = ".+"})
     *
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug)
    {
        $category = $this->getCategoryService()->findOneBySlug($slug);
        $products = $this->getProductService()->findByNestedCategories($category);

        return $this->render('AspetosShopBundle:Shop:category.html.twig', array(
            'products' => $products,
            'category' => $category,
        ));
    }

    /**
     * @return ProductService
     */
    protected function getProductService()
    {
        return $this->container->get('aspetos.service.product.product');
    }

    /**
     * @return CategoryService
     */
    protected function getCategoryService()
    {
        return $this->container->get('aspetos.service.product.category');
    }

    /**
     * @return Shop
     */
    protected function getShop()
    {
        return $this->container->get('aspetos.shop');
    }
}
