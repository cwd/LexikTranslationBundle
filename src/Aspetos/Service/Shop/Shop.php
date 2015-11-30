<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Shop;

use Aspetos\Model\Entity\CustomerOrder;
use Aspetos\Service\CustomerOrderService;
use Aspetos\Service\Log\InjectLoggerTrait;
use Aspetos\Service\ProductService;
use JMS\DiExtraBundle\Annotation as DI;
use Aspetos\Model\Entity\ProductCategory;

/**
 * This service represents all the "official" shopping logic, basically by linking all necessary
 * sub-services.
 * There should not be any actual logic in here.
 *
 * @package Aspetos\Service\Shop
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.shop")
 */
class Shop
{
    use InjectLoggerTrait;

    /**
     * @var CustomerOrderService
     */
    protected $orderService;

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * VAT rate. Might be set dynamically later on.
     *
     * @var double
     */
    protected $currentVat;

    /**
     * Shipping cost for non-virtual items. Might be set dynamically later on.
     *
     * @var double
     */
    protected $shippingCost;

    /**
     * Currency code. Currently hard-coded.
     *
     * @var string
     */
    protected $currencyCode = 'EUR';

    /**
     * Currency symbol to use for display. Currently hard-coded.
     *
     * @var string
     */
    protected $currencySymbol = '€';

    /**
     * @DI\InjectParams({
     *      "orderService" = @DI\Inject("aspetos.service.customer_order"),
     *      "productService" = @DI\Inject("aspetos.service.product.product"),
     *      "defaultVat" = @DI\Inject("%aspetos.shop.default_vat%"),
     *      "shippingCost" = @DI\Inject("%aspetos.shop.default_shipping_cost%")
     * })
     *
     * @param CustomerOrderService $orderService
     * @param ProductService       $productService
     * @param double               $defaultVat
     * @param double               $shippingCost
     */
    public function __construct(
        CustomerOrderService $orderService,
        ProductService $productService,
        $defaultVat,
        $shippingCost
    )
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->currentVat = $defaultVat;
        $this->shippingCost = $shippingCost;
    }

    /**
     * Check if there is an open order for the current session's user.
     *
     * @return bool
     */
    public function hasOpenOrder()
    {
        return $this->orderService->hasOpenOrder();
    }

    /**
     * Get an open CustomerOrder if available or create a new one which will be saved
     * to the database and the user's session.
     *
     * @return CustomerOrder
     */
    public function getOrCreateOrder()
    {
        $order = $this->orderService->getOrCreateOrder();
        $order->setShippingCost($this->getShippingCost($order));

        return $order;
    }

    /**
     * Update order information in session and/or database.
     *
     * @param CustomerOrder $order
     */
    public function updateOrder(CustomerOrder $order)
    {
        $this->orderService->updateOrder($order);
    }

    /**
     * Confirm order, saving it to the database.
     *
     * @param CustomerOrder $order
     */
    public function confirmOrder(CustomerOrder $order)
    {
        $this->orderService->confirmOrder($order);
    }

    /**
     * Find products for the given category, optionally including sub-categories in the search process.
     *
     * @param ProductCategory $category
     * @param bool            $includeSubCategories set to true to include nested sub-categories
     *
     * @return \Aspetos\Model\Entity\Product[]
     */
    public function findProductsForCategory(ProductCategory $category, $includeSubCategories = false)
    {
        if ($includeSubCategories) {
            return $this->productService->findByNestedCategories($category);
        }

        return $this->productService->findBySingleCategory($category);
    }

    /**
     * Get the net shipping cost for the given order.
     *
     * @param CustomerOrder $order
     * @return double
     */
    public function getShippingCost(CustomerOrder $order)
    {
        if ($order->isVirtual()) {
            return 0.0;
        }

        return $this->shippingCost;
    }

    /**
     * Calculate gross price including VAT for the given net value.
     *
     * @param mixed $price
     * @return double
     */
    public function net2gross($price)
    {
        return (100.0 + $this->currentVat) * $price / 100.0;
    }

    /**
     * Calculate VAT for the given net value.
     *
     * @param mixed $price
     * @return double
     */
    public function net2vat($price)
    {
        return $this->currentVat * $price / 100.0;
    }

    /**
     * Get the ISO 4217 code of the currently used currency.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get the symbol to use for displaying values in the current currency.
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

    /**
     * Get currently used VAT rate (in percent).
     *
     * @return double
     */
    public function getVatRate()
    {
        return $this->currentVat;
    }

    /**
     * Get a list of the most popular non-free products.
     *
     * @param int $limit
     * @return Product[]
     */
    public function getPopularProducts($limit = 10)
    {
        return $this->productService->findPopular($limit);
    }
}
