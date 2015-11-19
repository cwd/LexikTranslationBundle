<?php

namespace Aspetos\Bundle\ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Aspetos\Service\ProductService;
use Aspetos\Service\Product\CategoryService;

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
        $products = $this->getProductService()->findByCategory($category);

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
}
