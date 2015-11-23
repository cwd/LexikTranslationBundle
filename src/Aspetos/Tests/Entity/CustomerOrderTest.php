<?php

namespace Aspetos\Tests\Entity;

use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\CustomerOrder;
use Aspetos\Model\Entity\OrderItem;

/**
 * Class Aspetos\CustomerOrderTest
 *
 * @package Aspetos\Tests\Entity
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CustomerOrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideItemSets
     *
     * @param array  $productsByAmount
     * @param double $expectedSum
     */
    public function testUpdateTotalAmount($productsByAmount, $expectedSum)
    {
        $order = new CustomerOrder();
        foreach ($productsByAmount as $amount => $product) {
            $item = new OrderItem();
            $item
                ->setProduct($product)
                ->setAmount($amount);

            $order->addOrderItem($item);
        }
        $order->updateTotalAmount();

        $this->assertEquals($expectedSum, $order->getTotalAmount());
    }

    /**
     * @return array
     */
    public function provideItemSets()
    {
        $product1 = new Product();
        $product1->setSellPrice(7.0);

        $product2 = new Product();
        $product2->setSellPrice(13.0);

        return array(
            array(
                array(
                    1 => $product1,
                ),
                7.0,
            ),
            array(
                array(
                    3 => $product2,
                ),
                39.0,
            ),
            array(
                array(
                    2 => $product1,
                    3 => $product2,
                ),
                53.0,
            ),
        );
    }

    public function testMergeItemMergesItemForSameProduct()
    {
        $order = $this->getSampleOrder();

        $this->assertSame(2, count($order->getOrderItems()));
        $this->assertSame(5, $order->getOrderItems()[0]->getAmount());
        $this->assertSame(4, $order->getOrderItems()[1]->getAmount());
    }

    public function testGetNumberOfPositions()
    {
        $order = $this->getSampleOrder();

        $this->assertSame(2, $order->getNumberOfPositions());
    }

    public function testGetNumberOfItems()
    {
        $order = $this->getSampleOrder();

        $this->assertSame(9, $order->getNumberOfItems());
    }

    public function testCleanupItems()
    {
        $product = new Product();
        $product->setSellPrice(13.0);

        $order = new CustomerOrder();

        $orderItem = new OrderItem;
        $orderItem->setProduct($product)->setAmount(0);
        $order->addOrderItem($orderItem);

        $this->assertSame(1, $order->getNumberOfPositions());
        $order->cleanupOrderItems();
        $this->assertSame(0, $order->getNumberOfPositions());
    }

    public function testAddProductAddsProduct()
    {
        $order = new CustomerOrder();
        $order->addProduct(new Product(), 1);

        $this->assertSame(1, $order->getNumberOfPositions());
    }

    public function testAddProductRunsCleanup()
    {
        $order = $this->getSampleOrder();

        $item = $order->getOrderItems()[0];
        $order->addProduct($item->getProduct(), $item->getAmount() * -1);

        $this->assertSame(1, $order->getNumberOfPositions());
    }

    public function testMergeItemRunsCleanup()
    {
        $order = $this->getSampleOrder();

        $item = clone $order->getOrderItems()[0];
        $item->setAmount($item->getAmount() * -1);
        $order->mergeOrderItem($item);

        $this->assertSame(1, $order->getNumberOfPositions());
    }

    /**
     * Get sample order with some added and merged items.
     *
     * @return CustomerOrder
     */
    protected function getSampleOrder()
    {
        $product1 = new Product();
        $product1->setSellPrice(7.0);

        $product2 = new Product();
        $product2->setSellPrice(13.0);

        $order = new CustomerOrder();

        $orderItem = new OrderItem;
        $orderItem->setProduct($product1)->setAmount(2);
        $order->addOrderItem($orderItem);

        $orderItem = new OrderItem;
        $orderItem->setProduct($product1)->setAmount(3);
        $order->mergeOrderItem($orderItem);

        $orderItem = new OrderItem;
        $orderItem->setProduct($product2)->setAmount(4);
        $order->mergeOrderItem($orderItem);

        return $order;
    }
}
