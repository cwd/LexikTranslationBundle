<?php

namespace Aspetos\Tests\Entity;

use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\OrderItem;
/**
 * Class Aspetos\OrderItemTest
 *
 * @package Aspetos\Tests\Entity
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class OrderItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideItemPrices
     *
     * @param Product|null $product
     * @param int          $amount
     * @param double       $expectedSum
     */
    public function testUpdatePrice($product, $amount, $expectedSum)
    {
        $item = new OrderItem();
        if (null !== $product) {
            $item->setProduct($product);
        }
        $item->setAmount($amount);
        $item->updatePrice();

        $this->assertEquals($expectedSum, $item->getPrice());
    }

    /**
     *
     * @return array
     */
    public function provideItemPrices()
    {
        $product1 = new Product();
        $product1->setSellPrice(7.0);

        $product2 = new Product();
        $product2->setSellPrice(13.0);

        return array(
            array(
                $product1,
                1,
                7.0,
            ),
            array(
                $product2,
                3,
                39.0,
            ),
            array(
                null,
                3,
                0.0,
            ),
        );
    }
}
