<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Controller;

use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Service\Shop\Shop;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Aspetos\Model\Entity\OrderItem;
use Symfony\Component\Form\Form;

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
        return $this->render('AspetosShopBundle:Shop:index.html.twig', array(
        ));
    }

    /**
     * "popular products" list.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function popularProductsAction(Request $request)
    {
        $products = $this->getShop()->getPopularProducts();

        return $this->render('AspetosShopBundle:Shop:_productsCarousel.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Shopping cart page
     *
     * @Route("/einkaufswagen", name="aspetos_shop_cart")
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
        $form->handleRequest($request);
        if ($form->isValid()) {
            $order = $form->getData();
            $shop->updateOrder($order);

            if ($form->get('checkout')->isClicked()) {
                return $this->redirectToRoute('aspetos_shop_checkout');
            }

            return $this->redirectToRoute('aspetos_shop_index');
        }

        return $this->render('AspetosShopBundle:Shop:cart.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Shopping cart info. Used for page header indicator.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nanoCartAction(Request $request)
    {
        $shop = $this->getShop();
        $order = $shop->getOrCreateOrder();

        return $this->render('AspetosShopBundle:Shop:_nanoCart.html.twig', array(
            'order' => $order,
        ));
    }

    /**
     * Checkout page.
     *
     * @Route("/kasse", name="aspetos_shop_checkout")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        $shop = $this->getShop();
        $order = $shop->getOrCreateOrder();
        $form = $this->createForm('aspetos_shop_checkout', $order);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $order = $form->getData();
            $shop->confirmOrder($order);

            return $this->redirectToRoute('aspetos_shop_index');
        }

        return $this->render('AspetosShopBundle:Shop:checkout.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Product detail page.
     *
     * @Route("/produkt/{slug}", name="aspetos_shop_product")
     * @ParamConverter("product", class="Model:Product")
     *
     * @param Product $product
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productAction(Product $product, Request $request)
    {
        $form = $this->createAddToCartForm($product);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->processAddToCartForm($form);

            return $this->redirect($request->getUri());
        }

        return $this->render('AspetosShopBundle:Shop:product.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Account action
     *
     * @Route("/mein-konto", name="aspetos_shop_my_account")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myAccountAction(Request $request)
    {
        return $this->render('AspetosShopBundle:Shop:myAccount.html.twig', array(
        ));
    }

    /**
     * Shop category page.
     *
     * @Route("/kategorie/{slug}", name="aspetos_shop_category", requirements={"slug" = ".+"})
     * @ParamConverter("category", class="Model:ProductCategory")
     *
     * @param ProductCategory $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction(ProductCategory $category)
    {
        $products = $this->getShop()->findProductsForCategory($category, true);

        return $this->render('AspetosShopBundle:Shop:category.html.twig', array(
            'products' => $products,
            'category' => $category,
        ));
    }

    /**
     * @return Shop
     */
    protected function getShop()
    {
        return $this->container->get('aspetos.shop');
    }

    /**
     * Get an "add to cart" form for the given product (optional)
     *
     * @param Product $product
     * @return \Symfony\Component\Form\Form
     */
    protected function createAddToCartForm(Product $product = null)
    {
        $orderItem = new OrderItem();
        if (null !== $product) {
            $orderItem->setProduct($product);
        }
        $orderItem->setAmount(1);

        $form = $this->createForm('aspetos_shop_order_item', $orderItem);

        return $form;
    }

    /**
     * Process the given "add to cart" form, adding the booked item to the cart.
     * @TODO: error/success message handling
     *
     * @param Form $form
     */
    protected function processAddToCartForm(Form $form)
    {
        if ($form->isValid()) {
            /* @var $orderItem OrderItem */
            $orderItem = $form->getData();
            $shop = $this->getShop();
            $order = $shop->getOrCreateOrder();
            $order->mergeOrderItem($orderItem);
            $shop->updateOrder($order);
        }
    }
}
