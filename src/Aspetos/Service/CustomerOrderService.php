<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

use Aspetos\Model\Entity\CustomerOrder as Entity;
use Aspetos\Model\Entity\CustomerOrder;
use Aspetos\Model\Entity\OrderItem;
use Aspetos\Model\Repository\CustomerOrderRepository as EntityRepository;
use Aspetos\Service\Exception\CustomerOrderNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Aspetos Service CustomerOrder
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.customer_order", parent="cwd.generic.service.generic")
 */
class CustomerOrderService extends BaseService
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @param EntityManager    $entityManager
     * @param LoggerInterface  $logger
     * @param SessionInterface $session
     * @param ProductService   $productService
     *
     * @DI\InjectParams({
     *      "productService" = @DI\Inject("aspetos.service.product.product")
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger, SessionInterface $session, ProductService $productService)
    {
        parent::__construct($entityManager, $logger);

        $this->session = $session;
        $this->productService = $productService;
    }

    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setServiceOptions()
    {
        return array(
            'modelName'                 => 'Model:CustomerOrder',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\CustomerOrderNotFoundException',
        );
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
        $order->cleanupOrderItems();
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
        $order = $this->getNew();
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
