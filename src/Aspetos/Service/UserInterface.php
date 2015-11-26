<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aspetos\Service;
use Aspetos\Model\Entity\BaseUser;

/**
 * Interface UserInterface
 * @package Aspetos\Service
 */
interface UserInterface
{
    /**
     *
     * @return BaseUser
     */
    public function getUser();
}
