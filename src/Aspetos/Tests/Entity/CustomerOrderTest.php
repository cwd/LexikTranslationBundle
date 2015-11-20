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
}
