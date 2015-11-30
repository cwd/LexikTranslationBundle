<?php

namespace Aspetos\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AspetosUserBundle
 *
 * @package Aspetos\Bundle\UserBundle
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class AspetosUserBundle extends Bundle
{
    /**
     * Returns the bundle parent name.
     *
     * @return string The Bundle parent name it overrides or null if no parent
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
