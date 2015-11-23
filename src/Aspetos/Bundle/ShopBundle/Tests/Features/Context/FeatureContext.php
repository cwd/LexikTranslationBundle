<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Tests\Features\Context;

use Aspetos\Bundle\AdminBundle\Mink\Context\BaseContext;
use Behat\Mink\Exception\ExpectationException;

/**
 * Behat context class, using Mink.
 *
 * @author david
 */
class FeatureContext extends BaseContext
{
    /**
     * @Then /^the current main menu item should be "(?P<text>(?:[^"]|\\")*)"$/
     *
     * @param string $text
     */
    public function assertActiveMainMenuItem($text)
    {
        $this->assertElementContainsText('.header-navigation > .navbar-nav li.active', $text);
    }

    /**
     * @Then there should be :count items in the cart, totalling ":total"
     *
     * @param int    $count
     * @param string $total
     */
    public function assertItemsInNanoCart($count, $total)
    {
        $assert = $this->assertSession();
        $actual = $assert->elementExists('css', '.header .top-cart-info .top-cart-info-count')->getText();
        list ($actualCount, ) = explode(' ', $actual);
        if ($actualCount != $count) {
            throw new ExpectationException("Expected count of {$count}, but got {$actualCount} (text '{$actual}')", $this->getSession()->getDriver());
        }

        $this->assertElementContainsText('.header .top-cart-info .top-cart-info-value', "{$total}");
    }
}
