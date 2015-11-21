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
use Aspetos\Model\Entity\OrderItem;
use Aspetos\Service\CustomerOrderService;
use Aspetos\Service\Log\InjectLoggerTrait;
use Aspetos\Service\ProductService;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * This service implements all the fancy shopping logic.
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
     * @var SessionInterface
     */
    protected $session;

    /**
     * @DI\InjectParams({
     *      "orderService" = @DI\Inject("aspetos.service.customer_order"),
     *      "productService" = @DI\Inject("aspetos.service.product.product"),
     *      "session" = @DI\Inject("session")
     * })
     *
     * @param CustomerOrderService $orderService
     * @param ProductService       $productService
     * @param SessionInterface     $session
     */
    public function __construct(
        CustomerOrderService $orderService,
        ProductService $productService,
        SessionInterface $session
    )
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->session = $session;
    }

    /**
     * Check if there is an open order for the current session's user.
     * @TODO: check if there is an authenticated user with an open order accessible by database
     *
     * @return bool
     */
    public function hasOpenOrder()
    {
        return $this->session->has('aspetos.shop.order');
    }

    /**
     * Get an open CustomerOrder if available or create a new one which will be saved
     * to the database and the user's session.
     *
     * @TODO: check if there is an authenticated user with an open order accessible by database
     *
     * @return CustomerOrder
     */
    public function getOrCreateOrder()
    {
        $order = $this->buildOrderFromSessionData();

        return $order;
    }

    /**
     * Update order information in session and/or database.
     *
     * @param CustomerOrder $order
     */
    public function updateOrder(CustomerOrder $order)
    {
        $this->storeOrderInSession($order);
    }

    /**
     * Build CustomerOrder using session information.
     *
     * @return CustomerOrder
     */
    protected function buildOrderFromSessionData()
    {
        /* @var $order CustomerOrder */
        $order = $this->orderService->getNew();
        $orderData = $this->session->get('aspetos.shop.order', array());
        if (!isset($orderData['items'])) {
            return $order;
        }
        foreach ($orderData['items'] as $productId => $amount) {
            $product = $this->productService->findEnabledById($productId);
            if (null === $product) {
                continue;
            }

            $orderItem = new OrderItem();
            $orderItem
                ->setProduct($product)
                ->setAmount($amount);

            $order->addOrderItem($orderItem);
        }

        return $order;
    }

    /**
     * Save current order information to the session.
     *
     * @param CustomerOrder $order
     */
    protected function storeOrderInSession(CustomerOrder $order)
    {
        $orderData = array(
            'obituary' => null,
            'items' => array(),
        );
        foreach ($order->getOrderItems() as $orderItem) {
            $orderData['items'][$orderItem->getProduct()->getId()] = $orderItem->getAmount();
        }

        $this->session->set('aspetos.shop.order', $orderData);
    }

    /**
     * Remove current order from session. This should be used
     * when the order is completed.
     */
    protected function clearOrder()
    {
        $this->session->remove('aspetos.shop.order');
    }
}
