<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Tests\Features\Context;

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
     * @Then I will load the grid
     */
    public function iWillLoadTheGrid()
    {
        $url = $this->detectGridUrl();
        $this->visitPath($url);
        $this->assertResponseStatus(200);
    }

    protected function detectGridUrl()
    {
        $content = $this->getSession()->getDriver()->getContent();
        $found = preg_match('/var \$options = \{"sAjaxSource"\:"(.*)"\};/', $content, $matches);
        if (!$matches) {
            $message = "Could not detect grid URL in ".$this->getSession()->getCurrentUrl();
            throw new ExpectationException($message, $this->getSession());
        }

        return str_replace('\\', '', $matches[1]);
    }
}
