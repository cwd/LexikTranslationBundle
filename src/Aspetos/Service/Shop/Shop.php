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
     * @DI\InjectParams({
     *      "orderService" = @DI\Inject("aspetos.service.customer_order"),
     *      "productService" = @DI\Inject("aspetos.service.product.product")
     * })
     *
     * @param CustomerOrderService $orderService
     * @param ProductService       $productService
     */
    public function __construct(
        CustomerOrderService $orderService,
        ProductService $productService
    )
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
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
        return $this->orderService->getOrCreateOrder();
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
}
