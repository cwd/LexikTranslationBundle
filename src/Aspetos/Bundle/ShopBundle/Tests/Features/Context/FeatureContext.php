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
    public function theCurrentMainMenuItemIs($text)
    {
        $this->assertElementContainsText('.header-navigation > .navbar-nav li.active', $text);
    }
}
